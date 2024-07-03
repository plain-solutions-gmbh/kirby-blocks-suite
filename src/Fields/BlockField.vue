<template>
  <k-field class="k-block-field" v-bind="$props">
    <k-block-tabs
      ref="tabElement"
      :tab="currentTab"
      :tabs="parsedTabs"
      @update="setTab($event)"
    />
    <k-form
      v-for="(tab, index) in parsedTabs"
      :key="index"
      ref="editor"
      :value="value"
      v-bind="tab"
      class="k-block-form"
      :hidden="tab.name !== currentTab"
      @input="$emit('input', $event)"
      @invalid="setInvalid($event, index)"
    />
  </k-field>
</template>

<script>
export default {
  extends: "k-field",
  inheritAttrs: false,
  props: {
    tabs: {
      type: Object,
      default() {},
    },
    value: Object,
  },
  data() {
    return {
      currentTab: null,
      parsedTabs: [],
    };
  },
  created() {
    for (const [tabname, tab] of Object.entries(this.tabs)) {
      tab.name ??= tabname;
      Object.entries(tab.fields).forEach(([fieldName]) => {
        tab.fields[fieldName].section = this.name;
        tab.fields[fieldName].endpoints = {
          field: this.endpoints.field + "/fields/" + fieldName,
          section: this.endpoints.section,
          model: this.endpoints.model,
        };
      });
      this.parsedTabs.push(tab);
    }

    let availableTabs = Object.keys(this.tabs);
    this.currentTab =
      sessionStorage.getItem(`plain.blocks.tab.${this.hash}`) ?? "content";

    if (availableTabs.includes(this.currentTab) === false) {
      this.currentTab = availableTabs[0];
    }
  },
  methods: {
    setTab(tab) {
      this.currentTab = tab;
      sessionStorage.setItem(`plain.blocks.tab.${this.hash}`, tab);
    },
    setInvalid(validation, index) {
      this.parsedTabs[index].badge = "";
      Object.values(validation).forEach((field) => {
        if (field.$invalid) {
          this.parsedTabs[index].badge = "×";
        }
      });
      //this.$refs.tabElement.resize();
    },
  },
};
</script>

<style>
.k-block-field > .k-field-header {
  border-bottom: 1px solid;
}

.k-block-field > .k-tabs {
  margin-bottom: 0;
}

.k-block-field > .k-form {
  margin-top: var(--spacing-5);
  margin-bottom: var(--spacing-5);
}

.k-block-field .k-tabs-badge {
  border: none;
  box-shadow: none;
  background: none;
  font-weight: var(--spacing-12);
  color: var(--color-red-700);
}
</style>
