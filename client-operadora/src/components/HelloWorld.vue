<template>
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
      >
        <b-icon-plus /> Novo
      </button>
      <b-icon-pencil />
      <b-icon-x />
      <b-form-checkbox switch size="sm">Small</b-form-checkbox>
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
      >
        <b-icon-sliders />&nbsp;Habilitar ou Desabilitar
      </button>
    </div>

    <div>
      <form class="form-inline">
        <label for="">Operadora</label><br />
        <b-form-select v-model="selected" :options="status"></b-form-select>
        <label for="">Status</label><br />
        <b-form-select v-model="selected" :options="status"></b-form-select>
      </form>
    </div>
    <b-table
      striped
      hover
      ref="selectableTable"
      selectable
      :select-mode="selectMode"
      @row-selected="onRowSelected"
      :fields="fields"
      :items="items"
    />
    <div>
      <b-button id="show-btn" @click="showModal">Open Modal</b-button>
      <b-button id="toggle-btn" @click="toggleModal">Toggle Modal</b-button>

      <b-modal ref="my-modal" hide-footer title="Using Component Methods">
        <div class="d-block text-center">
          <h3>Hello From My Modal!</h3>
        </div>
        <b-button class="mt-3" variant="outline-danger" block @click="hideModal"
          >Close Me</b-button
        >
        <b-button
          class="mt-2"
          variant="outline-warning"
          block
          @click="toggleModal"
          >Toggle Me</b-button
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
} from "bootstrap-vue";

Vue.use(BootstrapVueIcons);
Vue.use(FormSelectPlugin);
Vue.use(ButtonPlugin);
Vue.use(ModalPlugin);
Vue.use(FormCheckboxPlugin);

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
          value: 2,
          text: "Inativo(s)",
        },
      ],
      allChecked: false,
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
      items: [
        { edit: true, operadora: 40, descricao: "Dickerson" },
        { edit: true, operadora: 21, descricao: "Larsen" },
        { edit: true, operadora: 89, descricao: "Geneva" },
        { edit: true, operadora: 38, descricao: "Jami" },
      ],
    };
  },
  methods: {
    onRowSelected(data) {
      let arr = [];
      if (data.length) {
          // arr.push({key: 'edit', label: 'Editar'});
          arr.push(this.fields);
      } else {
        arr.slice(0);
      }
      this.fields = arr;
    },

    showModal() {
      this.$refs["my-modal"].show();
    },

    hideModal() {
      this.$refs["my-modal"].hide();
    },

    toggleModal() {
      // We pass the ID of the button that we want to return focus to
      // when the modal has hidden
      this.$refs["my-modal"].toggle("#toggle-btn");
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
    postData: async function () {
      return await axios
        .create("http://localhost:8000/api/operadora/create", {
          operadora: operadora,
          descricao: descricao,
          status: status,
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
        .then((resp) => resp.data)
        .catch((err) => console.log(err));
    },
  },
  created() {
    this.getData();
  },
  computed: {
    selectAll: {
      get: function () {
        return true
          ? /*this.selected.length == this.users.length*/ true
          : false;
      },
      set: function (value) {
        var selected = [];

        // if (value) {
        //   this.users.forEach(function (user) {
        //     selected.push(user.id);
        //   });
        // }

        this.selected = selected;
      },
    },
  },
  watch: {
    allRecords() {
      if (this.allRecords === "accepted")
        this.$refs.selectableTable.selectAllRows();
      else this.$refs.selectableTable.clearSelected();
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
