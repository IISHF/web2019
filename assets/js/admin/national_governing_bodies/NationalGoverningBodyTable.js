import React from 'react';
import Table from '@material-ui/core/Table';
import TableBody from '@material-ui/core/TableBody';
import TableCell from '@material-ui/core/TableCell';
import TableHead from '@material-ui/core/TableHead';
import TableRow from '@material-ui/core/TableRow';
import CircularProgress from "@material-ui/core/CircularProgress";

import api from '../api';

class NationalGoverningBodyTable extends React.Component {
    state = {
        isLoading: false,
        items: [],
    };

    async componentDidMount() {
        await this.loadData();
    }

    async loadData() {
        this.setState({items: [], isLoading: true});
        const ngbs = await api(`/national-governing-bodies/list`);
        this.setState({items: ngbs, isLoading: false});
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
                        <TableCell>Country</TableCell>
                    </TableRow>
                </TableHead>
                <TableBody>
                    {this.state.items.map((i) => {
                        return (
                            <TableRow key={i.id} hover>
                                <TableCell component="th" scope="row">{i.name}</TableCell>
                                <TableCell>{i.country_name}</TableCell>
                            </TableRow>
                        );
                    })}
                </TableBody>
            </Table>
        );
    }
}

export default NationalGoverningBodyTable;