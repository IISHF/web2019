import React from "react";
import PropTypes from 'prop-types';
import {Editor, getEventRange, getEventTransfer} from "slate-react";
import {Block, Value} from "slate";
import Toolbar from "@material-ui/core/Toolbar";
import IconButton from "@material-ui/core/IconButton";
import {
    FormatBold,
    FormatItalic,
    FormatListBulleted,
    FormatListNumbered,
    FormatQuote,
    FormatSize,
    FormatUnderlined,
    InsertLink,
    InsertPhoto
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

function wrapLink(editor, href) {
    editor.wrapInline({
        type: 'link',
        data: {href},
    });

    editor.moveToEnd();
}

function unwrapLink(editor) {
    editor.unwrapInline('link');
}

function insertImage(editor, src, target) {
    if (target) {
        editor.select(target);
    }

    editor.insertBlock({
        type: 'image',
        data: {src},
    });
}

const schema = {
    document: {
        last: {type: 'paragraph'},
        normalize: (editor, {code, node, child}) => {
            switch (code) {
                case 'last_child_type_invalid': {
                    const paragraph = Block.create('paragraph');
                    return editor.insertNodeByKey(node.key, node.nodes.size, paragraph);
                }
            }
        },
    },
    blocks: {
        image: {
            isVoid: true,
        },
    },
};

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
        console.log(value.toJSON());
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

    hasLinks = () => {
        const {editorState} = this.state;
        return editorState.inlines.some(inline => inline.type === 'link')
    };

    renderNode = ({attributes, children, node, isFocused}, editor, next) => {
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
            case 'link':
                const {data} = node;
                const href = data.get('href');
                return (
                    <a {...attributes} href={href}>
                        {children}
                    </a>
                );
            case 'image':
                const src = node.data.get('src');
                const imgStyle = {
                    display: 'block',
                    maxWidth: '100%',
                    maxHeight: '20em',
                    boxShadow: isFocused ? '0 0 0 2px blue' : 'none'
                };
                return <img src={src} style={imgStyle} {...attributes} />;
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

        if (type === 'size') {
            if (this.hasBlock('heading-one')) {
                editor.setBlocks('heading-two');
            } else if (this.hasBlock('heading-two')) {
                editor.setBlocks('paragraph');
            } else {
                editor.setBlocks('heading-one');
            }
        } else if (type !== 'bulleted-list' && type !== 'numbered-list') {
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

    onClickLink = (event) => {
        event.preventDefault();

        const editor = this.editor.current;
        const {value} = editor;
        const hasLinks = this.hasLinks();

        if (hasLinks) {
            editor.command(unwrapLink)
        } else if (value.selection.isExpanded) {
            const href = window.prompt('Enter the URL of the link:');

            if (href === null) {
                return
            }

            editor.command(wrapLink, href)
        } else {
            const href = window.prompt('Enter the URL of the link:');

            if (href === null) {
                return
            }

            const text = window.prompt('Enter the text for the link:');

            if (text === null) {
                return
            }

            editor
                .insertText(text)
                .moveFocusBackward(text.length)
                .command(wrapLink, href)
        }
    };

    onClickImage = (event) => {
        event.preventDefault();

        const editor = this.editor.current;

        const src = window.prompt('Enter the URL of the image:');
        if (!src) {
            return;
        }
        editor.command(insertImage, src);
    };


    renderMarkButton = (type, icon) => {
        const isActive = this.hasMark(type);

        return this.renderButton((e) => this.onClickMark(e, type), isActive, icon);
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

        return this.renderButton((e) => this.onClickBlock(e, type), isActive, icon);
    };

    renderButton = (handler, isActive, icon) => {
        return (
            <IconButton color={isActive ? 'primary' : 'inherit'} onClick={handler}>{icon}</IconButton>
        );
    };

    onPaste = (event, editor, next) => {
        if (editor.value.selection.isCollapsed) {
            return next();
        }

        const transfer = getEventTransfer(event);
        const {type, text} = transfer;
        if (type !== 'text' && type !== 'html') {
            return next();
        }
        if (this.hasLinks()) {
            editor.command(unwrapLink)
        }
        editor.command(wrapLink, text)
    };

    onDrop = (event, editor, next) => {
        const target = getEventRange(event, editor);
        if (!target && event.type === 'drop') {
            return next();
        }

        const transfer = getEventTransfer(event);
        const {type, text, files} = transfer;

        if (type === 'files') {
            for (const file of files) {
                const reader = new FileReader();
                const [mime] = file.type.split('/');
                if (mime !== 'image') {
                    continue;
                }
                reader.addEventListener('load', () => {
                    editor.command(insertImage, reader.result, target)
                });
                reader.readAsDataURL(file);
            }
            return;
        }

        if (type === 'text') {
            editor.command(insertImage, text, target);
            return;
        }
        next();
    };

    render() {
        const {initialContent, ...other} = this.props;

        return (
            <>
                <Toolbar disableGutters={true}>
                    {this.renderButton(
                        (e) => this.onClickBlock(e, 'size'),
                        (this.hasBlock('heading-one') || this.hasBlock('heading-two')),
                        <FormatSize/>
                    )}
                    {this.renderMarkButton('bold', <FormatBold/>)}
                    {this.renderMarkButton('italic', <FormatItalic/>)}
                    {this.renderMarkButton('underlined', <FormatUnderlined/>)}
                    {this.renderBlockButton('bulleted-list', <FormatListBulleted/>)}
                    {this.renderBlockButton('numbered-list', <FormatListNumbered/>)}
                    {this.renderBlockButton('block-quote', <FormatQuote/>)}
                    {this.renderButton((e) => this.onClickLink(e), this.hasLinks(), <InsertLink/>)}
                    {this.renderButton((e) => this.onClickImage(e), this.hasLinks(), <InsertPhoto/>)}
                </Toolbar>
                <Editor spellCheck
                        {...other}
                        ref={this.editor}
                        value={this.state.editorState}
                        schema={schema}
                        onChange={this.onChange}
                        onDrop={this.onDrop}
                        onPaste={this.onPaste}
                        renderNode={this.renderNode}
                        renderMark={this.renderMark}
                />
            </>
        );
    }
}
