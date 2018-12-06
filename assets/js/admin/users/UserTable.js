import React from 'react';
import Table from '@material-ui/core/Table';
import TableBody from '@material-ui/core/TableBody';
import TableCell from '@material-ui/core/TableCell';
import TableHead from '@material-ui/core/TableHead';
import TableRow from '@material-ui/core/TableRow';
import CircularProgress from "@material-ui/core/CircularProgress";

import api from '../api';

class UserTable extends React.Component {
    state = {
        isLoading: false,
        items: [],
    };

    async componentDidMount() {
        await this.loadData();
    }

    async loadData() {
        this.setState({items: [], isLoading: true});
        const users = await api(`/users/list`);
        this.setState({items: users, isLoading: false});
    }

    render() {
        if (this.state.isLoading) {
            return <CircularProgress/>;
        }
        return (
            <Table>
                <TableHead>
                    <TableRow>
                        <TableCell>Name</TableCell>
                        <TableCell>Email</TableCell>
                    </TableRow>
                </TableHead>
                <TableBody>
                    {this.state.items.map((i) => {
                        return (
                            <TableRow key={i.id} hover>
                                <TableCell component="th" scope="row">{i.name}</TableCell>
                                <TableCell>{i.email}</TableCell>
                            </TableRow>
                        );
                    })}
                </TableBody>
            </Table>
        );
    }
}

export default UserTable;
