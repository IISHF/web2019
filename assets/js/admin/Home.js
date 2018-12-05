import React from 'react';
import Button from "@material-ui/core/Button";
import Typography from "@material-ui/core/Typography";

export default ({homeUrl}) => (
    <>
        <Typography variant="h3">Home</Typography>
        <Button variant="contained" color="primary" href={homeUrl}>Home</Button>
    </>
);
