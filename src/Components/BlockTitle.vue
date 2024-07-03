<template>
  <div class="k-block-title">
    <div v-if="hasImage" class="k-block-title-image-wrapper">
      <k-image-frame v-bind="image" />
    </div>
    <k-icon v-else :type="icon" class="k-block-icon" />
    <span v-if="name" class="k-block-name">
      {{ name }}
    </span>
    <span v-if="label" class="k-block-label">
      {{ label }}
    </span>
  </div>
</template>

<script>
export const props = {
  props: {
    /**
     * The block content is an object of values,
     * depending on the block type.
     */
    content: {
      default: () => ({}),
      type: [Array, Object],
    },
    /**
     * The fieldset definition with all fields, tabs, etc.
     */
    fieldset: {
      default: () => ({}),
      type: Object,
    },
  },
};

export default {
  mixins: [props],
  inheritAttrs: false,
  computed: {
    preview() {
      return this.fieldset.previewObj;
    },
    name() {
      let item = this.preview.name;
      return this.parse(item, this.fieldset.name ?? this.fieldset.label);
    },
    label() {
      let item = this.preview.label;
      let label = this.parse(item, this.fieldset.label);

      if (label === this.name) {
        return false;
      }

      return label;
    },
    icon() {
      let item = this.preview.icon;
      let icon = this.parse(item, this.fieldset.icon).toLowerCase();
      if (this.hasIcon(icon)) {
        return icon;
      }
      return item.default ?? this.fieldset.icon;
    },
    hasImage() {
      return this.preview.image !== null;
    },
    image() {
      let field = this.preview.image.field;
      let image = this.content[field][0]?.image ?? [];
      return Object.assign({}, this.preview.image, image);
    },
  },
  methods: {
    hasIcon(icon) {
      const iconElement = document.querySelector(
        "svg.k-icons > defs > #icon-" + icon
      );
      return iconElement !== null;
    },
    parse(preview, _default) {
      // Use preview field name as placeholder
      let value = preview?.field ? "{{" + preview.field + "}}" : _default ?? "";

      value = this.$helper.string.template(value, this.content);

      if (preview?.default) {
        _default = preview.default;
      }

      if (preview?.options) {
        value = preview.options[value] ?? _default;
      }

      if (value === "…" || value === undefined || value === "") {
        return _default ?? false;
      }

      value = this.$helper.string.stripHTML(value);
      return this.$helper.string.unescapeHTML(value);
    },
  },
};
</script>

<style>
.k-block-title > .k-block-title-image-wrapper {
  height: 2.8rem;
  margin-top: -0.75rem;
  margin-bottom: -0.75rem;
  margin-left: -0.75rem;
  margin-right: 0.5rem;
  border-top-left-radius: var(--rounded-md);
  border-bottom-left-radius: var(--rounded-md);
  overflow: hidden;
}

.k-block-title > .k-block-title-image-wrapper > .k-frame {
  height: 100%;
}
</style>
