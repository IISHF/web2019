import React from 'react';
import ErrorBoundary from './ErrorBoundary';

export function LoadingSpinner() {
    return (
        <div className="text-center">
            <div className="spinner-border" role="status">
                <span className="sr-only">Loading...</span>
            </div>
        </div>
    );
}

export function Lazy({children}) {
    return (
        <ErrorBoundary>
            <React.Suspense fallback={<LoadingSpinner/>}>
                {children}
            </React.Suspense>
        </ErrorBoundary>
    );
}
