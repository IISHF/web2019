import React from 'react';

export default class ErrorBoundary extends React.Component {
    constructor(props) {
        super(props);
        this.state = {hasError: false};
    }

    static getDerivedStateFromError(error) {
        return {hasError: true};
    }

    render() {
        if (this.state.hasError) {
            return (
                <div className="alert alert-warning" role="alert">
                    Something went wrong.
                </div>
            )
        }
        return this.props.children;
    }
}
