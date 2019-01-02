import React from "react";
import ReactMde from 'react-mde';
import {withField} from "./Form";
import FormControl from "@material-ui/core/FormControl";
import InputLabel from "@material-ui/core/InputLabel";

import '@fortawesome/fontawesome-free/css/all.css';
import 'react-mde/lib/styles/css/react-mde-all.css';

const TextEditor = (props) => {
    return (
        <FormControl disabled={props.disabled} error={props.error} required={props.required} fullWidth={true}>
            <InputLabel disabled={props.disabled} error={props.error} required={props.required}
                        disableAnimation={true}>
                {props.label}
            </InputLabel>
            <ReactMde
                value={props.value}
                onChange={text => props.form.setFieldValue(props.name, text, true)}
                generateMarkdownPreview={markdown =>
                    Promise.resolve(markdown)
                }
            />
        </FormControl>
    );
};

export default withField(TextEditor);
