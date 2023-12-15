import Alpine from 'alpinejs'

Alpine.data('teamShow', (teamId, countActivePlayers) => {
    return {
        teamId: teamId,
        players: [],
        countActivePlayers: countActivePlayers,
        uri: '/api/players/',
        teamPlayerUri: '/api/team/1/players',
        playerUri: '/player/',
        showList: true,
        showForm: false,
        editData: false,
        editPlayerId: null,
        actualPage: 1,
        pages: 0,
        formData: {
            name: '',
            surname: '',
            value: 0,
            expectedEndDate: '',
        },

        sellFormData: {
            name: '',
            playerTeamId: '',
            sellValue: 0,
        },

        sellFormErr: {
            sellValue: '',
        },

        formError: {
            name: [],
            surname: [],
        },

        closeForm() {
            this.showList = true;
            this.showForm = false;
        },

        initFormData(playerTeam = null) {
            console.log(this.playerTeam);

            this.formData.name = playerTeam ? playerTeam.player.name : '';
            this.formData.surname = playerTeam ? playerTeam.player.surname : '';
            this.formData.value = playerTeam ? playerTeam.amountValue : 0;
            this.formData.expectedEndDate = playerTeam && playerTeam.expectedEndDate ?
                new Date(playerTeam.expectedEndDate).toISOString().substring(0, 10) : '';
            this.formError = {
                name: '',
                surname: '',
            };
        },

        async retrievePlayers(page = this.actualPage) {
            const params = new URLSearchParams({
                page: page,
                teamId: this.teamId
            });
            let uri = this.uri + '?' + params.toString();
            let response = await (await fetch(uri)).json();
            this.players = response.results;
            this.countActivePlayers = response.total;
            this.actualPage = page;
            this.pages = response.pageNumber;
        },

        init() {
            this.retrievePlayers();
        },

        openForm() {
            this.showList = false;
            this.showForm = true;
        },

        addPlayer() {
            this.openForm();
            this.initFormData();
            this.editPlayerId = null;
        },

        editPlayer(id) {
            this.editPlayerId = id;
            this.openForm();
            let playerTeam = this.players.find(elt => elt.id === id);
            this.initFormData(playerTeam);
        },

        submitForm() {
            if (this.editPlayerId) {
                this.updatePlayer();
            } else {
                this.createPlayer();
            }
        },

        async firePlayer(id) {
            let uri = this.uri + id + '/fire';
            await fetch(uri, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            }).then((response) => {
                if (!response.ok) {
                    let err = new Error("HTTP status code: " + response.status)
                    err.response = response.json();
                    err.status = response.status
                    throw err
                }
                this.retrievePlayers();
            });
        },

        placeToMarket(id) {
            let playerTeam = this.players.find(elt => elt.id === id);
            this.sellFormData.name = playerTeam.player.name;
            this.sellFormData.playerTeamId = playerTeam.id;
            this.sellFormData.sellValue = playerTeam.sellingValue ?? playerTeam.amountValue;
            const buttonModal = document.querySelector('[x-ref="showModal"]');
            buttonModal.click();
        },

        async sellPlayer() {
            let uri = this.uri + this.sellFormData.playerTeamId;
            await fetch(uri, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify(this.sellFormData),
            }).then((response) => {
                if (!response.ok) {
                    let err = new Error("HTTP status code: " + response.status)
                    err.response = response.json();
                    err.status = response.status
                    throw err
                }
                this.retrievePlayers();
                const buttonModal = document.querySelector('[x-ref="close-modal"]');
                buttonModal.click();
            }).catch((err) => {
                err.response.then((data) => {
                    this.sellFormErr = data;
                });
            });
        },

        async createPlayer() {
            this.formData.expectedEndDate = this.formData.expectedEndDate === '' ?
                null : this.formData.expectedEndDate;
            console.log(this.formData);
            await fetch(this.teamPlayerUri, {
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
                this.retrievePlayers();
                this.closeForm();
            }).catch((err) => {
                err.response.then((data) => {
                    this.formError = data;
                });
            });
        },

        async updatePlayer() {
            this.formData.expectedEndDate = this.formData.expectedEndDate === '' ?
                null : this.formData.expectedEndDate;
            let uri = this.teamPlayerUri + '/' + this.editPlayerId;
            await fetch(uri, {
                method: "PUT",
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
                this.retrievePlayers();
                this.closeForm();
            });
        },

        showPlayer(id) {
            window.location.href = this.playerUri + id;
        }
    }
})
Alpine.start()