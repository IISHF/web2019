import React from 'react';
import Dropzone from 'react-dropzone';
import {fromEvent} from 'file-selector';
import fileSize from 'filesize';
import classNames from 'classnames';

export default class Upload extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            file: null
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
                file: null
            });
        }
    }

    render() {
        const file = this.state.file;

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
                                    <button type="button" className="btn btn-danger dz-remove"
                                            onClick={this.onRemove.bind(this)}>Remove
                                    </button>
                                    <img className="dz-thumbnail" src={file.preview}/>
                                </div>
                            ) : (
                                <p className="dz-message">Drop files here, or click to select files</p>
                            )}
                        </div>
                    )
                }}
            </Dropzone>
        );
    }
}
