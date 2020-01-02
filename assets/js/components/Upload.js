import React from 'react';
import PropTypes from 'prop-types';
import Dropzone from 'react-dropzone';
import {fromEvent} from 'file-selector';
import fileSize from 'filesize';
import classNames from 'classnames';
import Button from 'react-bootstrap/Button';
import api from '../api';

export default class Upload extends React.Component {
    static propTypes = {
        uploadUrl: PropTypes.string,
    };

    constructor(props) {
        super(props);
        this.state = {
            file: null,
            uploading: false,
        }
    }

    componentWillUnmount() {
        this.clearFile();
    }

    onDrop(files) {
        if (!files.length) {
            return;
        }
        const file = files[0];
        this.setState({
            file: Object.assign(file, {
                preview: URL.createObjectURL(file)
            })
        });
        if (this.props.uploadUrl) {
            const data = new FormData();
            data.append('file', file);

            this.setState({uploading: true});
            api.post(this.props.uploadUrl, data)
                .catch(() => this.clearFile())
                .finally(() => this.setState({uploading: true}));
        }
    }

    onCancel() {
        this.clearFile();
    }

    onRemove(e) {
        e.stopPropagation();
        this.clearFile();
    }

    clearFile() {
        if (this.state.file && this.state.file.preview) {
            URL.revokeObjectURL(this.state.file.preview);
            this.setState({
                file: null,
                uploading: false,
            });
        }
    }

    render() {
        const {file, uploading} = this.state;

        return (
            <Dropzone
                getDataTransferItems={e => fromEvent(e)}
                onDrop={this.onDrop.bind(this)}
                onFileDialogCancel={this.onCancel.bind(this)}
                maxSize={16000000}
                accept={'image/*'}
            >
                {({getRootProps, getInputProps, isDragActive, isDragAccept}) => {
                    let className = classNames(
                        'dropzone',
                        {'dz-drag-hover': isDragActive},
                        {'dz-drag-hover-ok': isDragAccept},
                        {'dz-drag-hover-nok': !isDragAccept}
                    );

                    return (
                        <div {...getRootProps()} className={className} style={{height: 300}}>
                            <input {...getInputProps()} />
                            {file ? (
                                <div className="dropzone-preview dz-image-preview">
                                    <div className="dz-size">{fileSize(file.size)}</div>
                                    <Button variant="danger" className="dz-remove" onClick={this.onRemove.bind(this)}>Remove
                                    </Button>
                                    <img className="dz-thumbnail" alt={file.name} src={file.preview}/>
                                </div>
                            ) : (
                                <p className="dz-message">Drop files here, or click to select files</p>
                            )}
                            {uploading && (
                                <div className="d-flex justify-content-center">
                                    <div className="spinner-border"
                                         style={{width: '10rem', height: '10rem'}}
                                         role="status">
                                        <span className="sr-only">Uploading...</span>
                                    </div>
                                </div>
                            )}
                        </div>
                    )
                }}
            </Dropzone>
        );
    }
}
