import Alpine from 'alpinejs'
Alpine.data('teams', (uri, teamBaseUri) => {
    return {
        teams: [],
        actualPage: 1,
        pages: 0,
        showList: true,
        headerTitle: 'Teams',
        uri: uri,
        baseUri: teamBaseUri,
        showForm: false,
        editData: false,
        editId: null,
        formData: {
            name: "",
            country: "",
            acountBalance: 0,
            players: [],
        },
        formError: {
            name: "",
            country: "",
            acountBalance: "",
            players: [],
        },

        formValid: false,

        showTeam(id) {
            window.location.href = this.baseUri + id;
        },

        openForm(header = 'New team') {
            this.showList = false;
            this.showForm = true;
            this.headerTitle = header;
        },

        createTeamForm() {
            this.openForm();
            this.initFormData();
            this.editData = false;
            console.log(!this.editData);
        },

        closeForm() {
            this.showList = true;
            this.showForm = false;
            this.headerTitle = 'Teams';
        },

        initFormData(team = null) {
            this.formData.name = team ? team.name : '';
            this.formData.country = team ? team.country : '';
            this.formData.acountBalance = team ? team.acountBalance : 0;
            this.formData.players = [];

            this.editData = false;
        },

        editTeam(teamId) {
            this.openForm('Update Team');
            let team = this.teams.find(elt => elt.id === teamId);
            this.editId = teamId;
            this.initFormData(team);
            this.editData = true;
            console.log(!this.editData);
        },

        addPlayer() {
            this.formData.players.push({
                name: '',
                surname: '',
                value: '',
            });
        },

        removePlayer(index) {
            this.formData.players.splice(index, 1);
        },

        async updateTeam() {
            let uri = this.uri + this.editId;
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

                return response.json();
            }).then((response) => {
                this.showTeam(response.id);
            }).catch((err) => {
                console.log(err);
                err.response.then((data) => {
                    this.formError = data;
                });
            });
        },

        submitForm() {
            if (this.editData) {
                this.updateTeam();
            } else {
                this.addTeam();
            }
        },

        async retrieveTeam(page = this.actualPage) {
            const params = new URLSearchParams({
                page: page
            });
            let uri = this.uri + '?' + params.toString();
            let response = await (await fetch(uri)).json();
            this.teams = response.results;
            this.actualPage = page;
            this.pages = response.pageNumber;
        },

        async removeTeam(teamId) {
            let uri = this.uri + teamId;

            await fetch(uri, {
                method: 'DELETE',
            }).then(() => {
                this.retrieveTeam();
            });
        },

        async addTeam() {
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
                this.showTeam(response.id);
            }).catch((err) => {
                console.log(err);
                err.response.then((data) => {
                    this.formError = data;
                });
            });
        },
    }
})
Alpine.start()
