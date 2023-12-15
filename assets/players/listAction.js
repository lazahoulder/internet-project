import Alpine from 'alpinejs'

Alpine.data('playerPage', () => {
    return {
        showList: true,
        showForm: false,
        showActive: true,
        showInactive: false,
        headerTitle: 'All players',
        teams: [],
        filteredTeams: [],
        teamsUri: '/api/team/light/list',
        playerUri: '/player/',
        uri: '/api/players/',
        editPlayerId: null,
        formData: {
            playerId: null,
            name: '',
            surname: '',
            teamId: null,
        },
        formError: {
            name: '',
            surname: '',
        },
        bidFormData: {
            name: '',
            playerTeamId: null,
            value: 0,
            teamId: null,
            sellerTeamId: '',
        },
        bidErrData: {
            value: '',
            teamId: '',
        },
        hireFormData: {
            name: '',
            playerId: null,
            value: 0,
            teamId: null,
            exceptedEndDate: '',
        },
        hireErrData: {
            teamId: '',
        },

        filterTeams(id) {
            this.filteredTeams = this.teams.filter(elt => elt.id !== id);
        },

        initFormData(player = null) {
            this.formData.playerId = player ? player.id : null;
            this.formData.name = player ? player.name : '';
            this.formData.surname = player ? player.surname : '';
            this.formData.teamId = null;
        },

        openForm(header = 'New player') {
            this.showList = false;
            this.showForm = true;
            this.headerTitle = header;
        },

        createPlayer() {
            this.editPlayerId = null;
            this.initFormData();
            this.openForm();
        },

        editPlayer(id) {
            this.formError = {
                name: '',
                surname: '',
            };
            this.editPlayerId = id;
            this.openForm('Edit Player');
            let playerTeam = null;
            if (typeof this.activePlayers === 'undefined') {
                playerTeam = this.inactivePlayers.find(elt => elt.id === id);
                this.initFormData(playerTeam);
            } else {
                playerTeam = this.activePlayers.find(elt => elt.player.id === id);
                this.initFormData(playerTeam.player);
            }
        },

        closeForm(header = 'All players') {
            this.showList = true;
            this.showForm = false;
            this.headerTitle = header;
        },

        async retrieveTeam() {
            this.teams = await (await fetch(this.teamsUri)).json();
        },

        init() {
            this.retrieveTeam();
        },

        submitForm() {
            this.addPlayer();
            this.formError = {
                name: '',
                surname: '',
            };
        },

        bidPlayer(id) {
            let playerTeam = this.activePlayers.find(elt => elt.id === id);
            this.bidFormData.name = playerTeam.player.name;
            this.bidFormData.playerTeamId = playerTeam.id;
            this.bidFormData.value = playerTeam.sellingValue ?? playerTeam.amountValue;
            this.filterTeams(playerTeam.team.id);
            console.log(this.filteredTeams);
            const buttonModal = document.querySelector('[x-ref="showModal"]');
            buttonModal.click();
        },

        hirePlayer(id) {
            let player = this.inactivePlayers.find(elt => elt.id === id);
            this.hireFormData.playerId = player.id;
            this.hireFormData.name = player.name;
            const buttonModal = document.querySelector('[x-ref="showModal"]');
            buttonModal.click();
        },

        submitModal() {
            if (this.showActive) {
                this.placeBid();
            } else if (this.showInactive) {
                this.hireNewPlayer();
            }
        },

        async placeBid() {
            let uri = '/api/bids/';
            await fetch(uri, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify(this.bidFormData),
            }).then((response) => {
                if (!response.ok) {
                    let err = new Error("HTTP status code: " + response.status)
                    err.response = response.json();
                    err.status = response.status
                    throw err
                }

                return response.json();
            }).then(() => {
                const reloadActiveButton = document.querySelector('[x-ref="reload-active"]');
                reloadActiveButton.click();
                const reloadInactiveButton = document.querySelector('[x-ref="reload-inactive"]')
                reloadInactiveButton.click();
                const buttonModal = document.querySelector('[x-ref="close-modal"]');
                buttonModal.click();
                this.bidErrData = {
                    value: '',
                    teamId: '',
                };
            }).catch((err) => {
                err.response.then((data) => {
                    this.bidErrData = data;
                });
            });
        },

        async hireNewPlayer() {
            this.hireFormData.exceptedEndDate = this.hireFormData.exceptedEndDate === '' ?
                null : this.hireFormData.exceptedEndDate;
            console.log(this.hireFormData);
            let uri = '/api/players/' + this.hireFormData.playerId;
            await fetch(uri, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify(this.hireFormData),
            }).then((response) => {
                if (!response.ok) {
                    let err = new Error("HTTP status code: " + response.status)
                    err.response = response.json();
                    err.status = response.status
                    throw err
                }

                return response.json();
            }).then(() => {
                const reloadActiveButton = document.querySelector('[x-ref="reload-active"]');
                reloadActiveButton.click();
                const reloadInactiveButton = document.querySelector('[x-ref="reload-inactive"]')
                reloadInactiveButton.click();
                const buttonModal = document.querySelector('[x-ref="close-modal"]');
                buttonModal.click();
                this.hireErrData = {
                    teamId: '',
                };
            }).catch((err) => {
                err.response.then((data) => {
                    this.hireErrData = data;
                });
            });
        },

        async addPlayer() {
            await fetch(this.uri, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify(this.formData),
            }).then((response) => {
                if (!response.ok) {
                    let err = new Error("HTTP status code: " + response.status)
                    err.response = response.json();
                    err.status = response.status
                    throw err
                }

                return response.json();
            }).then((response) => {
                this.closeForm();
                this.initFormData();
                const buttonElement = (response.teamId) ?
                    document.querySelector('[x-ref="active"]') : document.querySelector('[x-ref="inactive"]');
                buttonElement.click();

                const reloadActiveButton = document.querySelector('[x-ref="reload-active"]');
                reloadActiveButton.click();
                const reloadInactiveButton = document.querySelector('[x-ref="reload-inactive"]')
                reloadInactiveButton.click();
            }).catch((err) => {
                err.response.then((data) => {
                    this.formError = data;
                });
            });
        },
    }
})
Alpine.start()
