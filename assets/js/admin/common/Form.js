import React from "react";
import PropTypes from 'prop-types';
import {connect, Field as FormikField, Form as FormikForm, Formik, getIn} from "formik";
import Button from "@material-ui/core/Button";
import DateFnsUtils from '@date-io/date-fns';
import {MuiPickersUtilsProvider} from "material-ui-pickers";

export const Form = ({schema, onSubmit, children}) => {
    return (
        <Formik initialValues={schema.default()}
                validationSchema={schema}
                onSubmit={onSubmit}>
            {() => (
                <FormikForm>
                    <MuiPickersUtilsProvider utils={DateFnsUtils}>
                        {children}
                    </MuiPickersUtilsProvider>
                </FormikForm>
            )}
        </Formik>
    );
};

Form.propTypes = {
    schema: PropTypes.object.isRequired,
    onSubmit: PropTypes.func.isRequired,
};

export function withField(WrappedComponent) {
    function Field({name, label, forwardedRef}) {
        return (
            <FormikField name={name} ref={forwardedRef}>
                {(props) => {
                    const {field, form, disabled, innerRef, ...other} = props;
                    const {touched, errors, isSubmitting} = form;
                    const fieldError = getIn(errors, name);
                    const showError = getIn(touched, name) && !!fieldError;

                    return (<WrappedComponent
                        {...other}
                        {...field}
                        ref={innerRef}
                        label={label}
                        error={showError}
                        helperText={showError ? fieldError : props.helperText}
                        disabled={isSubmitting || disabled}
                        form={form}
                    />);
                }}
            </FormikField>
        );
    }

    return React.forwardRef((props, ref) => {
        return <Field {...props} forwardedRef={ref}/>;
    });
}

const SubmitButton = ({children, formik: {isSubmitting}, ...other}) => {
    return (
        <Button variant="contained" color="primary" {...other} disabled={isSubmitting} type="submit">{children}</Button>
    );
};

const ResetButton = ({children, formik: {isSubmitting}, ...other}) => {
    return (
        <Button variant="contained" color="primary" {...other} disabled={isSubmitting} type="reset">{children}</Button>
    );
};

export const Submit = connect(SubmitButton);
export const Reset = connect(ResetButton);
