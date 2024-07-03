<template>
  <k-dialog
    :cancel-button="false"
    :size="size"
    :submit-button="false"
    :visible="true"
    class="k-block-selector"
    @cancel="$emit('cancel')"
    @submit="$emit('submit', value)"
  >
    <k-headline v-if="headline">
      {{ headline }}
    </k-headline>

    <details
      v-for="(group, groupName) in groups"
      :key="groupName"
      :open="group.open"
    >
      <summary>{{ group.label }}</summary>
      <!-- fieldset.id instead of fieldset.type -->
      <k-navigate class="k-block-types">
        <k-button
          v-for="fieldset in group.fieldsets"
          :key="fieldset.name"
          :disabled="disabledFieldsets.includes(fieldset.id)"
          :icon="fieldset.icon ?? 'box'"
          :text="fieldset.name"
          size="lg"
          @click="$emit('submit', fieldset.id)"
          @focus.native="$emit('input', fieldset.id)"
        />
      </k-navigate>
    </details>
    <!-- eslint-disable vue/no-v-html -->
    <p
      class="k-clipboard-hint"
      v-html="$t('field.blocks.fieldsets.paste', { shortcut })"
    />
    <!-- eslint-enable -->
  </k-dialog>
</template>

<script>
export default {
  extends: "k-block-selector",
  inheritAttrs: false,
};
</script>
