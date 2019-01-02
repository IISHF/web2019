import React from 'react';
import Button from "@material-ui/core/Button";
import Typography from "@material-ui/core/Typography";
import * as Yup from 'yup';
import Paper from "@material-ui/core/es/Paper/Paper";
import {withStyles} from "@material-ui/core";
import {Form, Reset, Submit} from "./common/Form";
import TextField from './common/TextField';
import {DateField, DateTimeField, TimeField} from './common/DateTimeFields'
import TextEditor from "./common/TextEditor";

const FormSchema = Yup.object().shape({
    email: Yup.string()
        .default('')
        .email('Invalid email')
        .required('Required'),
    date: Yup.date()
        .default(new Date())
        .required('Required'),
    body: Yup.string()
        .default('**Hello world!!!**')
        .required('Required'),
});

const styles = (theme) => ({
    paper: {
        ...theme.mixins.gutters(),
        marginTop: theme.spacing.unit * 2,
        paddingTop: theme.spacing.unit * 2,
        paddingBottom: theme.spacing.unit * 2,
    },
    button: {
        marginTop: theme.spacing.unit * 2,
        marginRight: theme.spacing.unit * 2,
    },
});

const Home = ({homeUrl, classes}) => (
    <>
        <Typography variant="h3">Home</Typography>
        <Button variant="contained" color="primary" className={classes.button} href={homeUrl}>Home</Button>
        <Paper className={classes.paper}>
            <Form schema={FormSchema}
                  onSubmit={(values, {setSubmitting}) => {
                      console.log(values);
                      setTimeout(() => {
                          setSubmitting(false);
                      }, 1000);
                  }}
            >
                <TextField name="email" label="Email"/>
                <DateField name="date" label="Date"/>
                <TimeField name="date" label="Time"/>
                <DateTimeField name="date" label="Date/Time"/>
                <TextEditor name="body" label="Body"/>
                <Submit className={classes.button}>Submit</Submit>
                <Reset className={classes.button}>Reset</Reset>
            </Form>
        </Paper>
    </>
);

export default withStyles(styles)(Home);
