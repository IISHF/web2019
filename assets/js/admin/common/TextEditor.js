import React from "react";
import PropTypes from 'prop-types';
import {Editor} from "slate-react";
import {Value} from "slate";
import Toolbar from "@material-ui/core/Toolbar";
import IconButton from "@material-ui/core/IconButton";
import {
    FormatBold,
    FormatItalic,
    FormatListBulleted,
    FormatListNumbered,
    FormatQuote,
    FormatSize,
    FormatUnderlined
} from "@material-ui/icons";

const defaultValue = Value.fromJSON({
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
});

export class TextEditor extends React.Component {

    static propTypes = {
        content: PropTypes.object,
    };

    static defaultProps = {
        content: null
    };

    state = {
        editorState: this.props.content || defaultValue,
    };

    constructor(props) {
        super(props);
        this.editor = React.createRef();
    }

    onChange = ({value}) => {
        this.setState({editorState: value})
    };

    hasMark = (type) => {
        const {editorState} = this.state;
        return editorState.activeMarks.some(mark => mark.type === type)
    };

    hasBlock = (type) => {
        const {editorState} = this.state;
        return editorState.blocks.some(node => node.type === type)
    };

    renderNode = ({attributes, children, node}, editor, next) => {
        switch (node.type) {
            case 'block-quote':
                return <blockquote {...attributes}>{children}</blockquote>;
            case 'bulleted-list':
                return <ul {...attributes}>{children}</ul>;
            case 'heading-one':
                return <h1 {...attributes}>{children}</h1>;
            case 'heading-two':
                return <h2 {...attributes}>{children}</h2>;
            case 'list-item':
                return <li {...attributes}>{children}</li>;
            case 'numbered-list':
                return <ol {...attributes}>{children}</ol>;
            default:
                return next();
        }
    };

    renderMark = ({children, mark, attributes}, editor, next) => {
        switch (mark.type) {
            case 'bold':
                return <strong {...attributes}>{children}</strong>;
            case 'italic':
                return <em {...attributes}>{children}</em>;
            case 'underlined':
                return <u {...attributes}>{children}</u>;
            default:
                return next()
        }
    };

    onClickMark = (event, type) => {
        event.preventDefault();
        this.editor.current.toggleMark(type)
    };

    onClickBlock = (event, type) => {
        event.preventDefault();

        const editor = this.editor.current;
        const {value} = editor;
        const {document} = value;

        if (type !== 'bulleted-list' && type !== 'numbered-list') {
            const isActive = this.hasBlock(type);
            const isList = this.hasBlock('list-item');

            if (isList) {
                editor.setBlocks(isActive ? 'paragraph' : type)
                    .unwrapBlock('bulleted-list')
                    .unwrapBlock('numbered-list');
            } else {
                editor.setBlocks(isActive ? 'paragraph' : type);
            }
        } else {
            // Handle the extra wrapping required for list buttons.
            const isList = this.hasBlock('list-item');
            const isType = value.blocks.some(block => !!document.getClosest(block.key, parent => parent.type === type));

            if (isList && isType) {
                editor.setBlocks('paragraph')
                    .unwrapBlock('bulleted-list')
                    .unwrapBlock('numbered-list');
            } else if (isList) {
                editor.unwrapBlock(type === 'bulleted-list' ? 'numbered-list' : 'bulleted-list')
                    .wrapBlock(type);
            } else {
                editor.setBlocks('list-item').wrapBlock(type);
            }
        }
    };

    renderMarkButton = (type, icon) => {
        const isActive = this.hasMark(type);

        return (
            <IconButton color={isActive ? 'primary' : 'inherit'}
                        onClick={(e) => this.onClickMark(e, type)}>{icon}</IconButton>
        )
    };

    renderBlockButton = (type, icon) => {
        let isActive = this.hasBlock(type);

        if (['numbered-list', 'bulleted-list'].includes(type)) {
            const {editorState: {document, blocks}} = this.state;

            if (blocks.size > 0) {
                const parent = document.getParent(blocks.first().key);
                isActive = this.hasBlock('list-item') && parent && parent.type === type
            }
        }

        return (
            <IconButton color={isActive ? 'primary' : 'inherit'}
                        onClick={(e) => this.onClickBlock(e, type)}>{icon}</IconButton>
        )
    };

    render() {
        const {initialContent, ...other} = this.props;

        return (
            <>
                <Toolbar disableGutters={true}>
                    {this.renderMarkButton('bold', <FormatBold/>)}
                    {this.renderMarkButton('italic', <FormatItalic/>)}
                    {this.renderMarkButton('underlined', <FormatUnderlined/>)}
                    {this.renderBlockButton('bulleted-list', <FormatListBulleted/>)}
                    {this.renderBlockButton('numbered-list', <FormatListNumbered/>)}
                    {this.renderBlockButton('block-quote', <FormatQuote/>)}
                    <IconButton color="inherit">
                        <FormatSize/>
                    </IconButton>
                </Toolbar>
                <Editor spellCheck
                        {...other}
                        ref={this.editor}
                        value={this.state.editorState}
                        onChange={this.onChange}
                        renderNode={this.renderNode}
                        renderMark={this.renderMark}
                />
            </>
        );
    }
}
