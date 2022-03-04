export function checkoutData() {
    return {
        name: '',
        locations: [],
        configurations: [],
        eggs: [],

        //readonly, used for displaying information
        selected_location: {},
        selected_configuration: {},
        selected_egg: {},

        form_complete: false,

        /**
         * Init request chain
         * Using provided query params, we fetch the required data to populate the form
         *
         */
        async init() {
            await this.fetchLocations();

            let selectedLocation = this.getSelectedLocation();
            if (selectedLocation) {
                await this.fetchConfigurations(selectedLocation.id);
            }
        },

        /**
         * Get available locations
         * @return {void}
         */
        async fetchLocations() {
            let response = await axios.get('/api/checkout/locations')
            this.locations = response.data.map(location => {
                location.selected = this.getQueryParams().location == location.id;
                return location;
            });

            setTimeout(() => {
                this.updateSelectedLocation()
            }, 0)
        },

        /**
         * Update selected location
         * @note step 1
         * @return {void}
         */
        updateSelectedLocation() {
            let selected = document.querySelector('#location').value

            this.locations = this.locations.map(location => {
                location.selected = location.id == selected
                return location;
            })

            this.setSelectedLocation()

            //reset next steps
            this.fetchConfigurations(selected);
            this.eggs = []
            this.updateSelectedEgg();
        },


        /**
         * Get available configurations
         * @param locationId
         * @return {void}
         */
        async fetchConfigurations(locationId) {
            if (isNaN(locationId)) return;

            let response = await axios.get('/api/checkout/configurations/' + locationId)
            this.configurations = response.data.map(configuration => {
                configuration.selected = this.getQueryParams().configuration == configuration.id;
                return configuration;
            });

            setTimeout(() => {
                this.updateSelectedConfiguration()
            }, 0)
        },

        /**
         * Update selected configuration
         * @step 2
         * @return {void}
         */
        updateSelectedConfiguration() {
            let selected = document.querySelector('#configuration').value

            this.configurations = this.configurations.map(configuration => {
                configuration.selected = configuration.id == selected
                return configuration;
            })

            this.setSelectedConfiguration()

            //reset next step
            this.fetchEggs(selected)
        },


        /**
         * Get available locations
         * @param configurationId
         * @return {void}
         */
        async fetchEggs(configurationId) {
            if (isNaN(configurationId)) return;

            let response = await axios.get('/api/checkout/eggs/' + configurationId)
            this.eggs = response.data.map(egg => {
                egg.selected = this.getQueryParams().egg == egg.id;
                return egg;
            });

            setTimeout(() => {
                this.updateSelectedEgg()
            }, 0)
        },

        /**
         * Update selected configuration
         * @step 3
         * @return {void}
         */
        updateSelectedEgg() {
            let selected = document.querySelector('#egg').value

            this.eggs = this.eggs.map(egg => {
                egg.selected = egg.id == selected
                return egg;
            })

            this.setSelectedEgg()
            this.checkFormComplete();
        },

        /**
         * Check whether the form is complete or not
         */
        checkFormComplete() {
            if (!this.getSelectedLocation() || !this.getSelectedConfiguration() || !this.getSelectedEgg() || !this.name) {
                this.form_complete = false
                return;
            }
            this.form_complete = true;
        },

        setSelectedLocation() {
            this.selected_location = this.getSelectedLocation();
        },
        setSelectedConfiguration() {
            this.selected_configuration = this.getSelectedConfiguration();
        },
        setSelectedEgg() {
            this.selected_egg = this.getSelectedEgg();
        },
        getSelectedLocation() {
            return this.locations.find(location => location.selected === true)
        },
        getSelectedConfiguration() {
            return this.configurations.find(configuration => configuration.selected === true)
        },
        getSelectedEgg() {
            return this.eggs.find(egg => egg.selected === true)
        },

        /**
         * Get url params
         * @returns {module:url.URLSearchParams}
         */
        getQueryParams() {
            return new Proxy(new URLSearchParams(window.location.search), {
                get: (searchParams, prop) => searchParams.get(prop),
            });
        }
    }
}
