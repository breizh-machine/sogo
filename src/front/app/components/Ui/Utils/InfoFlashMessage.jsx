import FlashMessage from './FlashMessage'

class InfoFlashMessage extends FlashMessage {
    constructor(props) {
        super(props);
    }
}

InfoFlashMessage.defaultProps = { type: 'info' }

export default InfoFlashMessage