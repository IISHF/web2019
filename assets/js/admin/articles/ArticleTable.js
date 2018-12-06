import React from 'react';
import Table from '@material-ui/core/Table';
import TableBody from '@material-ui/core/TableBody';
import TableCell from '@material-ui/core/TableCell';
import TableHead from '@material-ui/core/TableHead';
import TableRow from '@material-ui/core/TableRow';
import Chip from "@material-ui/core/Chip";
import Typography from "@material-ui/core/Typography";
import TablePagination from "@material-ui/core/TablePagination";
import CircularProgress from "@material-ui/core/CircularProgress";

import api from '../api';

class ArticleTable extends React.Component {
    state = {
        isLoading: false,
        count: 0,
        page: 1,
        items: [],
    };

    static get defaultProps() {
        return {
            limit: 30,
            className: ''
        }
    }

    async componentDidMount() {
        await this.loadData(1);
    }

    async loadData(page) {
        this.setState({items: [], page: page, count: 0, isLoading: true});
        const articles = await api(`/articles/list?page=${page}&limit=${this.props.limit}`);
        this.setState({
            items: articles.rows,
            count: articles.count,
            isLoading: false
        });
    }

    handleChangePage = async (event, page) => {
        await this.loadData(page + 1);
    };

    render() {
        if (this.state.isLoading) {
            return <CircularProgress/>;
        }
        return (
            <div>
                <TablePagination
                    rowsPerPageOptions={[]}
                    component="div"
                    count={this.state.count}
                    rowsPerPage={this.props.limit}
                    page={this.state.page - 1}
                    onChangePage={this.handleChangePage}
                />
                <Table className={this.props.className}>
                    <TableHead>
                        <TableRow>
                            <TableCell>Title</TableCell>
                            <TableCell>Tags</TableCell>
                            <TableCell>Published</TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {this.state.items.map((i) => (
                                <TableRow key={i.id} hover>
                                    <TableCell component="th" scope="row">
                                        <Typography variant="subtitle2">
                                            {i.state === 'draft' && <Chip label="Draft"/>}
                                            {i.state === 'review' && <Chip label="Review"/>}
                                            {i.title}
                                        </Typography>
                                        <Typography variant="caption">{i.subtitle}</Typography>
                                    </TableCell>
                                    <TableCell>
                                        {i.tags.map((t) => (
                                            <Chip key={t} label={t}/>
                                        ))}
                                    </TableCell>
                                    <TableCell>
                                        <relative-time datetime={i.published_at}></relative-time>
                                    </TableCell>
                                </TableRow>
                            )
                        )}
                    </TableBody>
                </Table>
            </div>
        );
    }
}

export default ArticleTable;
