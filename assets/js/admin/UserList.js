import React from 'react';

async function jsonFetch(url) {
    const response = await fetch(url, {
        mode: 'cors'
    });
    return await response.json();
}

class UserList extends React.Component {
    state = {
        isLoading: false,
        items: [],
    };

    async componentDidMount() {
        await this.loadData();
    }

    async loadData() {
        this.setState({items: [], isLoading: true});
        const users = await jsonFetch(`${this.props.baseUrl}/users/list`);
        this.setState({items: users, isLoading: false});
    }

    render() {
        if (this.state.isLoading) {
            return <div>Loading</div>;
        }
        return (
            <ul>
                {this.state.items.map((i) => {
                    return <li key={i.id}>{i.name}, {i.email}</li>;
                })}
            </ul>
        );
    }
}

export default UserList;
