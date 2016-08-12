import FlashMessage from './FlashMessage'

class ErrorFlashMessage extends FlashMessage {
    constructor(props) {
        super(props);
    }
}

ErrorFlashMessage.defaultProps = { type: 'error' }

export default ErrorFlashMessage