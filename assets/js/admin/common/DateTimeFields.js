import React from "react";
import {
    DatePicker as MuiDatePicker,
    DateTimePicker as MuiDateTimePicker,
    TimePicker as MuiTimePicker
} from 'material-ui-pickers';
import {withField} from './Form';
import {AccessTime, DateRange, KeyboardArrowLeft, KeyboardArrowRight} from "@material-ui/icons";

const DatePicker = (props) => {
    return (
        <MuiDatePicker
            fullWidth
            margin="normal"
            leftArrowIcon={<KeyboardArrowLeft/>}
            rightArrowIcon={<KeyboardArrowRight/>}
            {...props}
            onChange={date => props.form.setFieldValue(props.name, date, true)}
        />
    );
};

const TimePicker = (props) => {
    return (
        <MuiTimePicker
            fullWidth
            margin="normal"
            ampm={false}
            {...props}
            onChange={date => props.form.setFieldValue(props.name, date, true)}
        />
    );
};

const DateTimePicker = (props) => {
    return (
        <MuiDateTimePicker
            fullWidth
            margin="normal"
            leftArrowIcon={<KeyboardArrowLeft/>}
            rightArrowIcon={<KeyboardArrowRight/>}
            dateRangeIcon={<DateRange/>}
            timeIcon={<AccessTime/>}
            ampm={false}
            {...props}
            onChange={date => props.form.setFieldValue(props.name, date, true)}
        />
    );
};

export const DateField = withField(DatePicker);
export const TimeField = withField(TimePicker);
export const DateTimeField = withField(DateTimePicker);
