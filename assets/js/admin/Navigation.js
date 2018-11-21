import React from 'react';
import List from "@material-ui/core/List/List";
import ListItem from "@material-ui/core/ListItem/ListItem";
import ListItemIcon from "@material-ui/core/ListItemIcon/ListItemIcon";
import ListItemText from "@material-ui/core/ListItemText/ListItemText";
import {NavLink} from "react-router-dom";
import withStyles from "@material-ui/core/styles/withStyles";

const styles = (theme) => ({
    active: {
        backgroundColor: theme.palette.primary.light,
    },
});

const Navigation = ({items, classes}) => (
    <List>
        {items.map((item) => (
            <li key={item.to}>
                <ListItem component={NavLink} exact={true} to={item.to}
                          activeClassName={classes.active}>
                    <ListItemIcon>{item.icon}</ListItemIcon>
                    <ListItemText primary={item.label}/>
                </ListItem>
            </li>
        ))}
    </List>
);

export default withStyles(styles)(Navigation);
