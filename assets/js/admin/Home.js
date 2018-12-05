import React from 'react';
import Button from "@material-ui/core/Button";
import Typography from "@material-ui/core/Typography";
import {Field, Form, Formik} from "formik";
import TextField from "@material-ui/core/TextField";
import {fieldToTextField} from 'formik-material-ui';
import * as Yup from 'yup';
import Paper from "@material-ui/core/es/Paper/Paper";
import {withStyles} from "@material-ui/core";
import {DatePicker, DateTimePicker, MuiPickersUtilsProvider, TimePicker} from 'material-ui-pickers';
import DateFnsUtils from '@date-io/date-fns';
import {AccessTime, DateRange, KeyboardArrowLeft, KeyboardArrowRight} from "@material-ui/icons";
import {Editor as SlateEditor} from 'slate-react';
import {Value as SlateValue} from 'slate';


class MySlateEditor extends React.Component {
    state = {
        editor: SlateValue.fromJSON({
            document: {
                nodes: [
                    {
                        object: 'block',
                        type: 'paragraph',
                        nodes: [
                            {
                                object: 'text',
                                leaves: [
                                    {
                                        text: 'A line of text in a paragraph.',
                                    },
                                ],
                            },
                        ],
                    },
                ],
            },
        }),
    };

    onChange = ({value}) => {
        this.setState({editor: value})
    };

    render() {
        return (
            <SlateEditor value={this.state.editor} onChange={this.onChange}/>
        );
    }
}

const FormSchema = Yup.object().shape({
    email: Yup.string()
        .default('')
        .email('Invalid email')
        .required('Required'),
    date: Yup.date()
        .default(new Date())
        .required('Required')
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
    },
});

const Home = ({homeUrl, classes}) => (
    <>
        <Typography variant="h3">Home</Typography>
        <Button variant="contained" color="primary" className={classes.button} href={homeUrl}>Home</Button>
        <Paper className={classes.paper}>
            <MuiPickersUtilsProvider utils={DateFnsUtils}>
                <Formik initialValues={FormSchema.default()}
                        validationSchema={FormSchema}
                        onSubmit={(values, {setSubmitting}) => {
                            console.log(values);
                            setTimeout(() => {
                                setSubmitting(false);
                            }, 1000);
                        }}>
                    {({isSubmitting}) => (
                        <Form>
                            <Field name="email">
                                {(props) => (
                                    <TextField
                                        {...fieldToTextField(props)}
                                        label="Email"
                                        margin="normal"
                                        fullWidth
                                    />
                                )}
                            </Field>
                            <Field name="date">
                                {(props) => (
                                    <DatePicker
                                        {...fieldToTextField(props)}
                                        label="Date"
                                        fullWidth
                                        margin="normal"
                                        onChange={date => props.form.setFieldValue(props.field.name, date, true)}
                                        leftArrowIcon={<KeyboardArrowLeft/>}
                                        rightArrowIcon={<KeyboardArrowRight/>}
                                    />
                                )}
                            </Field>
                            <Field name="date">
                                {(props) => (
                                    <TimePicker
                                        {...fieldToTextField(props)}
                                        label="Time"
                                        fullWidth
                                        margin="normal"
                                        onChange={date => props.form.setFieldValue(props.field.name, date, true)}
                                        ampm={false}
                                    />
                                )}
                            </Field>
                            <Field name="date">
                                {(props) => (
                                    <DateTimePicker
                                        {...fieldToTextField(props)}
                                        label="Date/Time"
                                        fullWidth
                                        margin="normal"
                                        onChange={date => props.form.setFieldValue(props.field.name, date, true)}
                                        leftArrowIcon={<KeyboardArrowLeft/>}
                                        rightArrowIcon={<KeyboardArrowRight/>}
                                        dateRangeIcon={<DateRange/>}
                                        timeIcon={<AccessTime/>}
                                        ampm={false}
                                    />
                                )}
                            </Field>
                            <Button disabled={isSubmitting} variant="contained" color="primary"
                                    className={classes.button} type="submit">Submit</Button>
                        </Form>
                    )}
                </Formik>
            </MuiPickersUtilsProvider>
            <MySlateEditor/>
        </Paper>
    </>
);

export default withStyles(styles)(Home);
