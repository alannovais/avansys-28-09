<template>
  <b-container>
    <div class="hello">
      <h1>Manter Operadora</h1>
      <div>
        <button
          type="button"
          class="btn"
          style="
            border-radius: 20px;
            background-color: #ccc7;
            box-shadow: 0.5px 0.5px #0004;
          "
          @click="manageData"
        >
          <b-icon-plus /> Novo
        </button>
      </div>
      <div>
        <b-form-checkbox
          id="checkbox-1"
          v-model="allRecords"
          name="checkbox-1"
          value="accepted"
          unchecked-value="not_accepted"
        />
        <button
          type="button"
          class="btn"
          style="
            border-radius: 5px;
            background-color: #ccc7;
            box-shadow: 0.5px 0.5px #0004;
          "
        >
          <b-icon-trash />&nbsp;Excluir
        </button>
        <button
          type="button"
          class="btn"
          style="
            border-radius: 5px;
            background-color: #ccc7;
            box-shadow: 0.5px 0.5px #0004;
          "
          @click="changeStatus"
        >
          <b-icon-sliders />&nbsp;Habilitar ou Desabilitar
        </button>
      </div>
      <form class="form-inline">
        <label for="">Operadora</label><br />
        <b-form-select
          v-model="selectedOperadora"
          :options="operadoraDropDown"
        ></b-form-select>
        <label for="">Status</label><br />
        <b-form-select v-model="selected" :options="status"></b-form-select>
      </form>
      <b-table
        striped
        hover
        ref="selectableTable"
        selectable
        :select-mode="selectMode"
        @row-selected="onRowSelected"
        :fields="fields"
        :items="items"
      >
        <template v-slot:cell(status)="row">
          <b-container>
            <b-row>
              <b-button variant="link" @click="manageData(row.item)">
                <b-icon-pencil />
              </b-button>
              <b-form-checkbox
                v-model="row.item.status"
                switch
                size="sm"
                style="margin-top: 5.5px"
              />
              <b-button variant="link" @click="deleteModal(row.item)">
                <b-icon-x />
              </b-button>
            </b-row>
          </b-container>
          <!-- <b-button size="sm" @click="row.toggleDetails" class="mr-2">
          {{ row.detailsShowing ? "Hide" : "Show" }} Details
        </b-button> -->
        </template>
      </b-table>
      <div>
        <b-modal ref="my-modal" hide-footer title="Gerenciamento Operadora">
          <div class="d-block text-center">
            <form class="form-inline">
              <b-form-input
                v-model="operadora"
                placeholder="Digite Operadora"
                required
              ></b-form-input>
              <b-form-input
                v-model="descricao"
                placeholder="Digite Descrição"
              ></b-form-input>
            </form>
          </div>
          <b-button
            class="mt-3"
            variant="outline-danger"
            block
            @click="hideModal"
            >Cancelar</b-button
          >
          <b-button
            class="mt-2"
            variant="outline-warning"
            block
            @click="saveData"
            >Salvar</b-button
          >
        </b-modal>

        <b-modal ref="modal-delete">
          <div class="d-block text-center">
            <h3>
              Tem certeza que deseja excluir a operadora
              <span v-if="itemDelete">{{ itemDelete.operadora }}</span
              >?
            </h3>
          </div>
          <b-button
            class="mt-3"
            variant="outline-danger"
            block
            @click="hideModal"
            >Não</b-button
          >
          <b-button
            class="mt-2"
            variant="outline-warning"
            block
            @click="deleteData(itemDelete.id)"
            >Sim</b-button
          >
        </b-modal>
      </div>
      <div
        class="modal fade"
        id="staticBackdrop"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">...</div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                Close
              </button>
              <button type="button" class="btn btn-primary">Understood</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </b-container>
</template>

<script>
/* eslint-disable */
import axios from "axios";
import Vue from "vue";
import {
  BButton,
  ButtonPlugin,
  BFormSelect,
  FormSelectPlugin,
  BTable,
  VBModal,
  ModalPlugin,
  BootstrapVueIcons,
  BFormCheckboxGroup,
  FormCheckboxPlugin,
  BContainer,
  LayoutPlugin,
  FormInputPlugin,
} from "bootstrap-vue";

Vue.use(BootstrapVueIcons);
Vue.use(FormSelectPlugin);
Vue.use(ButtonPlugin);
Vue.use(ModalPlugin);
Vue.use(FormCheckboxPlugin);
Vue.use(LayoutPlugin);
Vue.use(FormInputPlugin);

Vue.component("b-container", BContainer);
Vue.component("b-form-checkbox-group", BFormCheckboxGroup);
Vue.component("b-form-select", BFormSelect);
Vue.component("b-button", BButton);
Vue.component("b-table", BTable);
Vue.directive("b-modal", VBModal);

export default {
  name: "HelloWorld",
  data() {
    return {
      selectMode: "multi",
      selected: null,
      allRecords: false,
      descricao: "",
      selectedOperadora: null,
      operadoraDropDown: [
        {
          value: null,
          text: "Todos",
        },
      ],
      fields: [
        {
          key: "operadora",
          label: "Operadora",
        },
        {
          key: "descricao",
          label: "Descrição",
        },
      ],
      // items: [
      //   { status: false, operadora: 40, descricao: "Dickerson" },
      //   { status: true, operadora: 21, descricao: "Larsen" },
      //   { status: true, operadora: 89, descricao: "Geneva" },
      //   { status: true, operadora: 38, descricao: "Jami" },
      // ],
      itemDelete: "",
      status: [
        {
          value: null,
          text: "Todos",
        },
        {
          value: 1,
          text: "Ativo(s)",
        },
        {
          value: 0,
          text: "Inativo(s)",
        },
      ],
      operadora: "",
    };
  },
  methods: {
    manageData(e = null) {
      this.operadora = "";
      this.descricao = "";
      if (e != null) {
        this.operadora = e.operadora;
        this.descricao = e.descricao;
      }
      this.$refs["my-modal"].toggle("#toggle-btn");
    },

    saveData() {
      if (this.operadora && this.descricao) {
        return this.postData({
          operadora: this.operadora,
          descricao: this.descricao,
          status: 1,
        });
      }
    },
    changeStatus() {
      this.items.map((x) => (x.status = !x.status));
    },
    onRowSelected(data) {
      if (this.allRecords === "accepted" || data.length > 0) {
        return this.fields.unshift({
          key: "status",
          label: "Editar",
        });
      } else if (data.length === 0) {
        return this.fields.shift();
      }
    },

    showModal() {
      this.$refs["my-modal"].show();
    },

    hideModal() {
      this.$refs["my-modal"].hide();
    },

    deleteModal(e = null) {
      // We pass the ID of the button that we want to return focus to
      // when the modal has hidden
      this.itemDelete = e;
      this.$refs["modal-delete"].toggle("#toggle-btn");
    },

    getData: async function () {
      return await axios
        .get("http://localhost:8000/api/operadora")
        .then((resp) => resp.data)
        .catch((err) => console.log(err));
    },
    getOneData: async function (id) {
      return await axios
        .get(`http://localhost:8000/api/operadora/${id}`)
        .then((resp) => resp.data)
        .catch((err) => console.log(err));
    },
    postData: async function (data) {
      console.log(data);
      return await axios
        .post("http://localhost:8000/api/operadora/create", {
          operadora: data.operadora,
          descricao: data.descricao,
          status: data.status.toString(),
        })
        .then((resp) => resp.data)
        .catch((err) => console.log(err));
    },
    updateData: async function (id) {
      return await axios
        .update(`http://localhost:8000/api/operadora/update/${id}`, {
          operadora: operadora,
          descricao: descricao,
          status: status,
        })
        .then((resp) => resp.data)
        .catch((err) => console.log(err));
    },
    deleteData: async function (id) {
      return await axios
        .update(`http://localhost:8000/api/operadora/delete/${id}`)
        .then((resp) => {
          this.$refs["modal-delete"].toggle("#toggle-btn");
          return resp.data;
        })
        .catch((err) => console.log(err));
    },
  },
  created() {
    this.getData();
    this.items.map((x) => {
      this.operadoraDropDown.push({
        value: x.operadora,
        text: x.operadora,
      });
    });
  },
  computed: {
    items() {
      let arr = [
        { status: 0, operadora: 40, descricao: "Dickerson" },
        { status: 1, operadora: 21, descricao: "Larsen" },
        { status: 1, operadora: 89, descricao: "Geneva" },
        { status: 1, operadora: 38, descricao: "Jami" },
      ];

      if (this.selected != null && this.selectedOperadora != null) {
        return arr.filter(
          (x) =>
            x.status == this.selected && this.selectedOperadora == x.operadora
        );
      }

      if (this.selected != null || this.selectedOperadora != null) {
        return arr.filter(
          (x) =>
            x.status == this.selected || this.selectedOperadora == x.operadora
        );
      }

      return arr;
    },
  },
  watch: {
    allRecords() {
      if (this.allRecords === "accepted")
        this.$refs.selectableTable.selectAllRows();
      else this.$refs.selectableTable.clearSelected();
    },
    selected() {
      return this.items;
    },
    selectedOperadora() {
      return this.items;
    },
  },
};
</script>

<!-- Add 'scoped' attribute to limit CSS to this component only -->
<style scoped>
h1,
h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
