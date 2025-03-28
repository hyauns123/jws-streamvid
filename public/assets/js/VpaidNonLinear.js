/** @unrestricted */
const VpaidNonLinear = class {
    constructor() {
        /**
         * The slot is the div element on the main page that the ad is supposed to
         * occupy.
         * @private {Object}
         */
        this.slot_ = null;

        /**
         * The video slot is the video element used by the ad to render video
         * content.
         * @private {Object}
         */
        this.videoSlot_ = null;

        /**
         * An object containing all registered events. These events are all
         * callbacks for use by the VPAID ad.
         * @private {Object}
         */
        this.eventsCallbacks_ = {};

        /**
         * A list of getable and setable attributes.
         * @private {Object}
         */
        this.attributes_ = {
            'companions': '',
            'desiredBitrate': 256,
            'duration': 10,
            'expanded': false,
            'height': 0,
            'icons': '',
            'linear': false,
            'skippableState': false,
            'viewMode': 'normal',
            'width': 0,
            'volume': 1.0,
            'view_ad': 'View Ad'
        };

        /**
         * When the ad was started.
         * @private {number}
         */
        this.startTime_ = 0;

        /**
         * A set of ad playback events to be reported.
         * @private {Object}
         */
        this.quartileEvents_ = [
            { event: 'AdImpression', value: 0 }, { event: 'AdVideoStart', value: 0 },
            { event: 'AdVideoFirstQuartile', value: 25 },
            { event: 'AdVideoMidpoint', value: 50 },
            { event: 'AdVideoThirdQuartile', value: 75 },
            { event: 'AdVideoComplete', value: 100 }
        ];

        /**
         * @private {number} An index into what quartile was last reported.
         */
        this.nextQuartileIndex_ = 0;

        /**
         * Parameters passed in from the AdParameters section of the VAST.
         * Used for video URL and MIME type.
         * @private {!Object}
         */
        this.parameters_ = {};
    }

    /**
     * Returns the supported VPAID verion.
     * @param {string} version
     * @return {string}
     */
    handshakeVersion(version) {
        return ('2.0');
    }

    /**
     * Initializes all attributes in the ad. The ad will not start until startAd
     * is called.
     * @param {number} width The ad width.
     * @param {number} height The ad height.
     * @param {string} viewMode The ad view mode.
     * @param {number} desiredBitrate The desired bitrate.
     * @param {Object} creativeData Data associated with the creative.
     * @param {Object} environmentVars Runtime variables associated with the
     *     creative like the slot and video slot.
     */
    initAd(width, height, viewMode, desiredBitrate, creativeData, environmentVars) {
        this.attributes_['width'] = width;
        this.attributes_['height'] = height;
        this.attributes_['viewMode'] = viewMode;
        this.attributes_['desiredBitrate'] = desiredBitrate;

        // slot and videoSlot are passed as part of the environmentVars
        this.slot_ = environmentVars.slot;
        this.videoSlot_ = environmentVars.videoSlot;

        // Parse the incoming ad parameters.
        this.parameters_ = JSON.parse(creativeData['AdParameters']);

        this.log( 'initAd ' + width + 'x' + height + ' ' + viewMode + ' ' + desiredBitrate );
        this.callEvent_('AdLoaded');
    }

    /**
     * Helper function to update the size of the video player.
     * @private
     */
    updateVideoPlayerSize_() {
        this.videoSlot_.setAttribute('width', this.attributes_['width']);
        this.videoSlot_.setAttribute('height', this.attributes_['height']);
    }

    /**
     * Called by the wrapper to start the ad.
     */
    startAd() {

        this.log('Starting ad');

        const date = new Date();
        this.startTime_ = date.getTime();

        // Create a div to contain our ad elements.
        const image = this.parameters_.image || [];
  
        const container = document.createElement('div');
        container.setAttribute( 'id', 'container' );
        container.style.display = 'block';
        container.style.position = 'absolute';
        container.style.maxWidth = '95%';
        container.style.maxHeight = '100%';
        container.style.width = image.width + 'px';

        if (image.position == 'bottom') {
            container.style.bottom = '0';
            container.style.transform = 'translate(-50%,0%)';
            container.style.left = '50%';          
        }else{
            container.style.transform = 'translate(-50%,-50%)';
            container.style.left = '50%';
            container.style.top = 'calc( 50% + 20px )';
        }
       
        this.slot_.appendChild(container);

        const videos = this.parameters_.videos || [];

        if ( videos.length > 0)  {
            // Create a div to serve as a button to go from a non-linear ad to linear.
            const linearButton = document.createElement('div');
            linearButton.style.background = '#ce1212';
            linearButton.style.display = 'block';
            linearButton.style.margin = 'auto';
            linearButton.style.position = 'absolute';
            linearButton.style.top = '0px';
            linearButton.style.left = '0px';
            linearButton.style.textAlign = 'center';
            linearButton.style.color = '#fff';
            linearButton.style.padding = '5px 10px';
            linearButton.style.fontSize = '13px';
            linearButton.style.fontFamily = 'sans-serif';
            linearButton.style.cursor = 'pointer';
            linearButton.innerHTML = this.attributes_['view_ad'];
            linearButton.addEventListener('click', this.linearButtonClick_.bind(this), false);
            container.appendChild(linearButton);
        }

        const closeButton = document.createElement('button');
        closeButton.innerHTML = '&times;'
        closeButton.style.background = '#ce1212';
        closeButton.style.color = '#fff';
        closeButton.style.position = 'absolute';
        closeButton.style.top = '-10px';
        closeButton.style.right = '-10px';
        closeButton.style.border = 'none';
        closeButton.style.fontSize = '1rem';
        closeButton.style.boxShadow = 'none';
        closeButton.style.borderRadius = '50%';
        closeButton.style.height = '25px';
        closeButton.style.width = '25px';
        closeButton.style.cursor = 'pointer';
        closeButton.addEventListener('click', this.closeClick_.bind(this), false);
        container.appendChild(closeButton);


        // Create an img tag and populate it with the image passed in to the ad
        // parameters.
        const adImg = document.createElement('img');
        adImg.src = image.url || '';
        adImg.style.margin = 'auto';
        adImg.style.display = 'block';
        adImg.style.cursor = 'pointer';
        adImg.style.maxWidth = '100%';

        adImg.addEventListener('click', this.adClick_.bind(this), false);
        container.appendChild(adImg);

        this.callEvent_('AdStarted');
        this.callEvent_('AdImpression');
    }

    /**
     * Called when the non-linear ad is clicked.
     * @private
     */
    adClick_() {
        if ('AdClickThru' in this.eventsCallbacks_) {
            this.eventsCallbacks_['AdClickThru']('', '0', true);
        }
    }

    closeClick_() {
        this.slot_.removeChild(this.slot_.firstChild);
    }

    /**
     * Called when the linear overlay is clicked.  Plays the video passed in the
     * parameters.
     * @private
     */
    linearButtonClick_() {
        this.log('Linear Button Click');
        // This will turn the ad into a linear ad.
        this.attributes_.linear = true;
        this.callEvent_('AdLinearChange');
        // Remove all elements.
        while (this.slot_.firstChild) {
            this.slot_.removeChild(this.slot_.firstChild);
        }

        this.updateVideoPlayerSize_();

        // Start a video.
        const videos = this.parameters_.videos || [];
        for (let i = 0; i < videos.length; i++) {
            // Choose the first video with a supported mimetype.
            if (this.videoSlot_.canPlayType(videos[i].mimetype) != '') {
                this.videoSlot_.setAttribute('src', videos[i].url);

                // Set start time of linear ad to calculate remaining time.
                const date = new Date();
                this.startTime_ = date.getTime();

                this.videoSlot_.addEventListener(
                    'timeupdate', this.timeUpdateHandler_.bind(this), false);
                this.videoSlot_.addEventListener(
                    'loadedmetadata', this.loadedMetadata_.bind(this), false);
                this.videoSlot_.addEventListener(
                    'ended', this.stopAd.bind(this), false);

                this.videoSlot_.play();

                return;
            }
        }
        // Haven't found a video, so error.
        this.callEvent_('AdError');
    }

    /**
     * Called by the video element when the video reaches specific points during
     * playback.
     * @private
     */
    timeUpdateHandler_() {
        if (this.nextQuartileIndex_ >= this.quartileEvents_.length) {
            return;
        }
        const percentPlayed =
            this.videoSlot_.currentTime * 100.0 / this.videoSlot_.duration;
        let nextQuartile = this.quartileEvents_[this.nextQuartileIndex_];
        if (percentPlayed >= nextQuartile.value) {
            this.eventsCallbacks_[nextQuartile.event]();
            this.nextQuartileIndex_ += 1;
        }
        if (this.videoSlot_.duration > 0) {
            this.attributes_['remainingTime'] =
                this.videoSlot_.duration - this.videoSlot_.currentTime;
        }
    }

    /**
     * Called by the video element when video metadata is loaded.
     * @private
     */
    loadedMetadata_() {
        // The ad duration is not known until the media metadata is loaded.
        // Then, update the player with the duration change.
        this.attributes_['duration'] = this.videoSlot_.duration;
        this.callEvent_('AdDurationChange');
    }

    /**
     * Called by the wrapper to stop the ad.
     */
    stopAd() {
        this.log('Stopping ad');
        // Calling AdStopped immediately terminates the ad. Setting a timeout allows
        // events to go through.
        const callback = this.callEvent_.bind(this);
        setTimeout(callback, 75, ['AdStopped']);
    }

    /**
     * Called when the video player changes the width/height of the container.
     * @param {number} width The new width.
     * @param {number} height A new height.
     * @param {string} viewMode A new view mode.
     */
    resizeAd(width, height, viewMode) {
        this.log('resizeAd ' + width + 'x' + height + ' ' + viewMode );
        this.attributes_['width'] = width;
        this.attributes_['height'] = height;
        this.attributes_['viewMode'] = viewMode;
        this.updateVideoPlayerSize_();
        this.callEvent_('AdSizeChange');
    }

    /**
     * Pauses the ad.
     */
    pauseAd() {
        this.log('pauseAd');
        this.videoSlot_.pause();
        this.callEvent_('AdPaused');
    }

    /**
     * Resumes the ad.
     */
    resumeAd() {
        this.log('resumeAd');
        this.videoSlot_.play();
        this.callEvent_('AdPlaying');
    }

    /**
     * Expands the ad.
     */
    expandAd() {
        this.log('expandAd');
        this.attributes_['expanded'] = true;
        this.callEvent_('AdExpanded');
    }

    /**
     * Collapses the ad.
     */
    collapseAd() {
        this.log('collapseAd');
        this.attributes_['expanded'] = false;
    }

    /**
     * Skips the ad.
     */
    skipAd() {
        this.log('skipAd');
        if (this.attributes_['skippableState']) {
            this.callEvent_('AdSkipped');
        }
    }

    /**
     * Registers a callback for an event.
     * @param {Function} callback The callback function.
     * @param {string} eventName The callback type.
     * @param {Object} context The context for the callback.
     */
    subscribe(callback, eventName, context) {
        this.log('Subscribe ' + eventName);
        this.eventsCallbacks_[eventName] = callback.bind(context);
    }

    /**
     * Removes a callback based on the eventName.
     * @param {string} eventName The callback type.
     */
    unsubscribe(eventName) {
        this.log('unsubscribe ' + eventName);
        this.eventsCallbacks_[eventName] = null;
    }

    /**
     * Returns whether the ad is linear.
     * @return {boolean} True if the ad is a linear, false for non linear.
     */
    getAdLinear() {
        return this.attributes_['linear'];
    }

    /**
     * Returns ad width.
     * @return {number} The ad width.
     */
    getAdWidth() {
        return this.attributes_['width'];
    }

    /**
     * Returns ad height.
     * @return {number} The ad height.
     */
    getAdHeight() {
        return this.attributes_['height'];
    }

    /**
     * Returns true if the ad is expanded.
     * @return {boolean}
     */
    getAdExpanded() {
        this.log('getAdExpanded');
        return this.attributes_['expanded'];
    }

    /**
     * Returns the skippable state of the ad.
     * @return {boolean}
     */
    getAdSkippableState() {
        this.log('getAdSkippableState');
        return this.attributes_['skippableState'];
    }

    /**
     * Returns the remaining ad time, in seconds.
     * @return {number} The time remaining in the ad.
     */
    getAdRemainingTime() {
        const date = new Date();
        const currentTime = date.getTime();
        const remainingTime =
            this.attributes_.duration - (currentTime - this.startTime_) / 1000.0;
        return remainingTime;
    }

    /**
     * Returns the duration of the ad, in seconds.
     * @return {number} The duration of the ad.
     */
    getAdDuration() {
        return this.attributes_['duration'];
    }

    /**
     * Returns the ad volume.
     * @return {number} The volume of the ad.
     */
    getAdVolume() {
        this.log('getAdVolume');
        return this.attributes_['volume'];
    }

    /**
     * Sets the ad volume.
     * @param {number} value The volume in percentage.
     */
    setAdVolume(value) {
        this.attributes_['volume'] = value;
        this.log('setAdVolume ' + value);
        this.callEvent_('AdVolumeChange');
    }

    /**
     * Returns a list of companion ads for the ad.
     * @return {string} List of companions in VAST XML.
     */
    getAdCompanions() {
        return this.attributes_['companions'];
    }

    /**
     * Returns a list of icons.
     * @return {string} A list of icons.
     */
    getAdIcons() {
        return this.attributes_['icons'];
    }

    /**
     * Logs events and messages.
     * @param {string} message
     */
    log(message) {

    }

    /**
     * Calls an event if there is a callback.
     * @param {string} eventType
     * @private
     */
    callEvent_(eventType) {
        if (eventType in this.eventsCallbacks_) {
            this.eventsCallbacks_[eventType]();
        }
    }
};

/**
 * Main function called by wrapper to get the VPAID ad.
 * @return {Object} The VPAID compliant ad.
 */
var getVPAIDAd = function() {
    return new VpaidNonLinear();
};