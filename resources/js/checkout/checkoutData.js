export function checkoutData() {
    return {
        name: '',
        locations: [],
        configurations: [],
        nests: [],
        eggs: [],

        //readonly, used for displaying information
        //Alpine likes wants to watch props, not functions
        selected_location: {},
        selected_configuration: {},
        selected_egg: {},

        //check whether the form is completely filled in
        form_complete: false,

        /**
         * Init request chain
         */
        async init() {
            await this.fetchNests();
        },

        /**
         * Get available nests with eggs
         * @step 1
         * @return {void}
         */
        async fetchNests() {
            let response = await axios.get('/api/checkout/nests')
            this.nests = response.data.map(nest => {
                nest.eggs.map(egg => {
                    egg.selected = this.getQueryParams().egg == egg.id;
                    return egg
                })
                return nest;
            });

            //set eggs
            this.eggs = [];
            this.nests.forEach(nest => {
                this.eggs.push(...nest.eggs)
            })

            setTimeout(() => {
                this.updateSelectedEgg()
            }, 0)
        },

        /**
         * Update selected configuration
         * @step 1
         * @return {void}
         */
        updateSelectedEgg() {
            let selected = document.querySelector('#egg').value

            this.eggs = this.eggs.map(egg => {
                egg.selected = egg.id == selected
                return egg;
            })

            this.setSelectedEgg()

            //clear next step
            this.fetchLocations(selected);
        },

        /**
         * Get available locations
         * @step 2
         * @param eggId
         * @return {void}
         */
        async fetchLocations(eggId) {
            if (isNaN(eggId)) return;
            let response = await axios.get('/api/checkout/locations/' + eggId)
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
         * @step 2
         * @return {void}
         */
        updateSelectedLocation() {
            let selected = document.querySelector('#location').value

            this.locations = this.locations.map(location => {
                location.selected = location.id == selected
                return location;
            })

            this.setSelectedLocation()

            //reset next step
            this.fetchConfigurations(selected);
        },

        /**
         * Get available configurations
         * @step 3
         * @param locationId
         * @return {void}
         */
        async fetchConfigurations(locationId) {
            if (isNaN(locationId)) return;
            let egg = this.getSelectedEgg();

            let response = await axios.get(`/api/checkout/configurations/${egg.id}/${locationId}`)
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
         * @step 3
         * @return {void}
         */
        updateSelectedConfiguration() {
            let selected = document.querySelector('#configuration').value

            this.configurations = this.configurations.map(configuration => {
                configuration.selected = configuration.id == selected
                return configuration;
            })

            this.setSelectedConfiguration()
        },

        /**
         * Check whether the form is complete or not
         * This validates the continue button
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
