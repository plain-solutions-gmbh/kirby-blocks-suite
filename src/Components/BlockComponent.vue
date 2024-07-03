<script>
export default {
  extends: "k-block",
  inheritAttrs: false,
  computed: {
    tabs() {
      const tabs = this.fieldset.tabs ?? {};
      const previewFields = Object.keys(this.fieldset.previewObj.fields);
      for (const [tabName, tab] of Object.entries(tabs)) {
        for (const [fieldName] of Object.entries(tab.fields ?? {})) {
          // Remove PreviewFields from Drawer
          if (previewFields.includes(fieldName)) {
            delete tabs[tabName].fields[fieldName];
            continue;
          }

          tabs[tabName].fields[fieldName].section = this.name;
          tabs[tabName].fields[fieldName].endpoints = {
            field:
              this.endpoints.field +
              "/fieldsets/" +
              // Fieldset should taken from name
              (this.name ?? this.type.replace("/", "__")) +
              "/fields/" +
              fieldName,
            section: this.endpoints.section,
            model: this.endpoints.model,
          };
        }
        // Remove Tab from drawer if empty or preview
        if (
          Object.keys(tabs[tabName].fields).length === 0 ||
          this.fieldset.previewObj.tab === tabName
        ) {
          delete tabs[tabName];
        }
      }
      return tabs;
    },
  },
};
</script>
