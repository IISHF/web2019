import React from 'react';
import withStyles from "@material-ui/core/styles/withStyles";
import classNames from 'classnames';
import Divider from '@material-ui/core/Divider';
import IconButton from '@material-ui/core/IconButton';
import ChevronLeftIcon from '@material-ui/icons/ChevronLeft';
import Drawer from "@material-ui/core/Drawer/Drawer";

function createStyles(width) {
    return (theme) => ({
        open: {
            width: width,
            transition: theme.transitions.create('width', {
                easing: theme.transitions.easing.sharp,
                duration: theme.transitions.duration.enteringScreen,
            }),
        },
        close: {
            transition: theme.transitions.create('width', {
                easing: theme.transitions.easing.sharp,
                duration: theme.transitions.duration.leavingScreen,
            }),
            overflowX: 'hidden',
            width: theme.spacing.unit * 7 + 1,
            [theme.breakpoints.up('sm')]: {
                width: theme.spacing.unit * 9 + 1,
            },
        },

        toolbar: {
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'flex-end',
            padding: '0 8px',
            ...theme.mixins.toolbar,
        },
    });
}

const SideMenu = (props) => {
    return (
        <Drawer variant="permanent"
                className={classNames(props.className, {
                    [props.classes.open]: props.open,
                    [props.classes.close]: !props.open,
                })}
                classes={{
                    paper: classNames({
                        [props.classes.open]: props.open,
                        [props.classes.close]: !props.open,
                    }),
                }}
                open={props.open}>
            <div className={props.classes.toolbar}>
                <IconButton onClick={props.onClose}>
                    <ChevronLeftIcon/>
                </IconButton>
            </div>
            <Divider/>
            {props.children}
        </Drawer>
    );
};

export default (props) => {
    const handleDrawerClose = () => {
        if (props.onClose) {
            props.onClose();
        }
    };

    const StyledDrawer = withStyles(createStyles(props.width))(SideMenu);

    return (
        <StyledDrawer variant="permanent" className={props.className} open={props.open} onClose={handleDrawerClose}>
            {props.children}
        </StyledDrawer>
    );
};
