import React from 'react';
import Button from "@material-ui/core/Button/Button";

export default ({homeUrl}) => (
    <Button variant="contained" color="primary" href={homeUrl}>
        Hello World
    </Button>
);
