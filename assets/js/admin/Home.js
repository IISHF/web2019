import React from 'react';
import Button from "@material-ui/core/Button/Button";
import UserList from "./UserList";

export default ({homeUrl, baseUrl}) => (
    <>
        <Button variant="contained" color="primary" href={homeUrl}>Home</Button>
        <UserList baseUrl={baseUrl}/>
    </>
);
