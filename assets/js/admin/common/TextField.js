import React from "react";
import MuiTextField from "@material-ui/core/TextField";
import {withField} from './Form';

const TextField = (props) => {
    return (
        <MuiTextField
            fullWidth
            margin="normal"
            {...props}
        />
    );
};

export default withField(TextField);
