<template>
    <div class="container">
        <h1>Distributeur de friandise</h1>
        <notifications group="foo" />
        <table class="table col-7">
            <thead>
            <tr>
                <th style="width: 40%" scope="col">Produit</th>
                <th style="width: 15%" scope="col">Qte restante</th>
                <th style="width: 15%" scope="col">Qte</th>
                <th style="width: 30%" scope="col" class="text-center" colspan="2">Actions</th>
            </tr>
            </thead>
            <tr v-for="produit in listeProduit">
                <td>{{ produit.nomProduit }}</td>
                <td>{{ produit.qteRestante }}</td>
                <td><input class="form-control" type="text" v-model="produit.qteToUpdate"></td>
                <td>
                    <button class="btn btn-primary" v-on:click="addActivite('A',produit)">Acheter</button>
                </td>
                <td>
                    <button class="btn btn-success" v-on:click="addActivite('V',produit)">Vendre</button>
                </td>
            </tr>
        </table>

    </div>
</template>

<script>
    const axios = require('axios');

    export default {
        name: 'app',
        data: function () {
            return {
                listeProduit: []
            }
        },
        mounted() {
            this.getProduits();

        },
        methods: {
            getProduits: function () {
                let that = this;
                axios
                    .get('/getProduits')
                    .then(function(response) {
                        let self = that;
                        response.data.forEach((item) => {
                            item.qteToUpdate = 0;
                            self.listeProduit.push(item);
                        });
                    })
            },
            addActivite(typeActivite, produit){
                let self = this;
                if(produit.qteToUpdate == 0){
                    this.$notify({
                        group: 'foo',
                        type: 'warn',
                        title: 'Important message',
                        text: 'Quantité à mettre à jour à 0'
                    });
                }else {
                    axios.post('/addActivite', {
                        typeActivite: typeActivite,
                        quantite: produit.qteToUpdate,
                        produitId: produit.id
                    })
                        .then(function (response) {
                            produit.qteRestante = response.data;
                            self.$notify({
                                group: 'foo',
                                title: 'Important message',
                                text: 'Quantité restante mise à jour !'
                            });
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },

        }
    };
</script>