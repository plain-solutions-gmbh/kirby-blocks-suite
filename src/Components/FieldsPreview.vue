<template>
  <div :data-collapsed="collapsed" @dblclick="$emit('open')">
    <header class="k-block-type-fields-header">
      <k-block-title
        :content="values"
        :fieldset="fieldset"
        @click.native="toggle"
      />
      <k-icon
        type="angle-down"
        :style="'transform: rotate(' + (collapsed ? 0 : '-180deg') + ')'"
        @click.native="toggle"
      />
      <!-- Remove k-drawer-tabs -->
    </header>

    <!-- Add endpoints -->
    <k-form
      v-if="!collapsed"
      ref="form"
      :autofocus="true"
      :disabled="disabled"
      :fields="fields"
      :endpoints="endpoints"
      :value="values"
      class="k-block-type-fields-form"
      @input="$emit('update', $event)"
    />
  </div>
</template>

<script>
export default {
  extends: "k-block-type-fields",
  props: {
    fieldset: Object,
    endpoints: Object,
    type: String,
  },
  computed: {
    preview() {
      return this.fieldset.previewObj;
    },
    fields() {
      // Take fields from previewFields prop and add endpoints
      const fields = this.preview.fields ?? {};
      for (const [fieldName] of Object.entries(fields)) {
        fields[fieldName].section = this.name;
        fields[fieldName].endpoints = {
          field:
            this.endpoints.field +
            "/fieldsets/" +
            (this.name ?? this.type.replace("/", "__")) +
            "/fields/" +
            fieldName,
          section: this.endpoints.section,
          model: this.endpoints.model,
        };
      }
      return fields;
    },
  },
};
</script>

<style>
.k-block-container-type-fields.k-block-container:not(:last-of-type) {
  border-bottom: 0;
}

.k-block-type-fields {
  padding: 0.75rem 0;
}

.k-block-type-fields .k-block-type-fields-header .k-icon {
  cursor: pointer;
}

.k-block-type-fields .k-form {
  background: transparent;
  padding: var(--spacing-6) var(--spacing-2) var(--spacing-2);
}

.k-block-type-fields .k-block-title {
  padding: 0;
}
</style>
