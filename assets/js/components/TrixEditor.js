import React from 'react';
import PropTypes from 'prop-types';
import Trix from 'trix';
import api from '../api';

Trix.config.attachments.preview.caption.name = false;
Trix.config.attachments.preview.caption.size = false;
Trix.config.attachments.file.caption.size = false;

export default class TrixEditor extends React.Component {
    static propTypes = {
        name: PropTypes.string,
        value: PropTypes.string,
        onChange: PropTypes.func,
        onEditor: PropTypes.func,
        autofocus: PropTypes.bool,
        placeholder: PropTypes.string,
        uploadUrl: PropTypes.string,
        allowUpload: PropTypes.bool.isRequired,
        allowDelete: PropTypes.bool.isRequired,
    };

    static defaultProps = {
        name: 'content',
        autofocus: false,
        placeholder: 'Enter text here...',
        allowUpload: true,
        allowDelete: false,
    };

    inputId = null;
    editorRef = null;

    constructor(props) {
        super(props);
        this.inputId = 'input_' + Math.random().toString(36);
        this.editorRef = React.createRef();

        this.onEditorInitialize = this.onEditorInitialize.bind(this);
        this.onEditorChange = this.onEditorChange.bind(this);
        this.onFileAccept = this.onFileAccept.bind(this);
        this.onAddAttachment = this.onAddAttachment.bind(this);
        this.onRemoveAttachment = this.onRemoveAttachment.bind(this);
    }

    componentDidMount() {
        const editorEl = this.editorRef.current;
        editorEl.addEventListener('trix-initialize', this.onEditorInitialize);
        editorEl.addEventListener('trix-change', this.onEditorChange);
        editorEl.addEventListener('trix-file-accept', this.onFileAccept);
        editorEl.addEventListener('trix-attachment-add', this.onAddAttachment);
        editorEl.addEventListener('trix-attachment-remove', this.onRemoveAttachment);
    }

    componentWillUnmount() {
        const editorEl = this.editorRef.current;
        editorEl.removeEventListener("trix-initialize", this.onEditorInitialize);
        editorEl.removeEventListener("trix-change", this.onEditorChange);
        editorEl.removeEventListener('trix-file-accept', this.onFileAccept);
        editorEl.removeEventListener("trix-attachment-add", this.onAddAttachment);
        editorEl.removeEventListener('trix-attachment-remove', this.onRemoveAttachment);
    }

    onEditorInitialize(e) {
        if (this.props.onEditor) {
            const editorEl = this.editorRef.current;
            this.props.onEditor(editorEl.editor, editorEl);
        }
    }

    onEditorChange(e) {
        if (this.props.onChange) {
            const value = e.target.value;
            this.props.onChange(value);
        }
    }

    onFileAccept(e) {
        const {allowUpload, uploadUrl} = this.props;
        if (!allowUpload || !uploadUrl) {
            e.preventDefault();
        }
    }

    onAddAttachment(e) {
        const attachment = e.attachment;
        const {allowUpload, uploadUrl} = this.props;
        if (allowUpload && uploadUrl && attachment.file) {
            const data = new FormData();
            data.append('file', attachment.file);

            api.post(uploadUrl, data)
                .then((response) => {
                    return response.json();
                })
                .then((response) => {
                    if (response.url && response.href) {
                        attachment.setAttributes(response);
                    } else {
                        throw new Error('Response was not ok.');
                    }
                })
                .catch(() => attachment.remove());
        }
    }

    onRemoveAttachment(e) {
        const attachment = e.attachment;
        const {allowDelete} = this.props;
        if (allowDelete && attachment.file) {
            api.delete(attachment.getURL())
                .catch(() => attachment.remove());
        }
    }

    render() {
        const {name, value, autofocus, placeholder} = this.props;

        return (
            <div className="trix-content">
                <input id={this.inputId} value={value} type="hidden" name={name}/>
                <trix-editor
                    ref={this.editorRef}
                    input={this.inputId}
                    placeholder={placeholder}
                    autofocus={autofocus}/>
            </div>
        );
    }
}
