import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import Typography from '@material-ui/core/Typography';
import withStyles from "@material-ui/core/styles/withStyles";
import IconButton from "@material-ui/core/IconButton/IconButton";
import MenuIcon from '@material-ui/icons/Menu';
import AccountCircle from '@material-ui/icons/AccountCircle';
import classNames from 'classnames';
import SideMenu from './SideMenu';

const drawerWidth = 240;

const styles = (theme) => ({
    root: {
        display: 'flex',
    },

    toolbar: {
        paddingRight: 24,
    },

    appBar: {
        zIndex: theme.zIndex.drawer + 1,
        transition: theme.transitions.create(['width', 'margin'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
    },
    appBarShift: {
        marginLeft: drawerWidth,
        width: `calc(100% - ${drawerWidth}px)`,
        transition: theme.transitions.create(['width', 'margin'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.enteringScreen,
        }),
    },

    appBarSpacer: theme.mixins.toolbar,

    menuButton: {
        marginLeft: 12,
        marginRight: 36,
    },

    sideMenu: {
        flexShrink: 0,
        whiteSpace: 'nowrap',
    },

    title: {
        flexGrow: 1,
    },

    content: {
        flexGrow: 1,
        padding: theme.spacing.unit * 3,
        height: '100vh',
        overflow: 'auto',
    },
});

class AppFrame extends React.Component {
    state = {
        open: false,
    };

    constructor(props) {
        super(props);
        this.handleDrawerOpen = this.handleDrawerOpen.bind(this);
        this.handleDrawerClose = this.handleDrawerClose.bind(this);
    }

    handleDrawerOpen = () => {
        this.setState({open: true});
    };

    handleDrawerClose = () => {
        this.setState({open: false});
    };

    render() {
        const {nav, classes, children} = this.props;

        return (
            <div className={classes.root}>
                <CssBaseline/>
                <AppBar position="absolute"
                        className={classNames(classes.appBar, {
                            [classes.appBarShift]: this.state.open,
                        })}
                >
                    <Toolbar disableGutters={!this.state.open} className={classes.toolbar}>
                        {!this.state.open &&
                        <IconButton color="inherit"
                                    onClick={this.handleDrawerOpen}
                                    className={classes.menuButton}>
                            <MenuIcon/>
                        </IconButton>
                        }
                        <Typography component="h1" variant="h6" color="inherit" noWrap className={classes.title}>
                            IISHF - Administration
                        </Typography>
                        <IconButton color="inherit">
                            <AccountCircle/>
                        </IconButton>
                    </Toolbar>
                </AppBar>
                <SideMenu className={classes.sideMenu} width={drawerWidth}
                          classes={{open: {width: drawerWidth}}}
                          open={this.state.open} onClose={this.handleDrawerClose}>
                    {nav}
                </SideMenu>
                <main className={classes.content}>
                    <div className={classes.appBarSpacer}/>
                    {children}
                </main>
            </div>
        );
    }
}

export default withStyles(styles)(AppFrame);
