<script>
export default {
  extends: "k-tabs",
  inheritAttrs: false,
  methods: {
    button(tab) {
      return {
        link: tab.link,
        current: tab.name === this.current,
        icon: tab.icon,
        title: tab.label,
        text: tab.label ?? tab.text ?? tab.name,
        //Add click emitter
        click: () => this.$emit("update", tab.name),
      };
    },
  },
};
</script>
