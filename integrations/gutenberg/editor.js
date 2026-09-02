(function (wp) {
    "use strict";

    var registerBlockType = wp.blocks.registerBlockType;
    var createElement = wp.element.createElement;
    var useState = wp.element.useState;
    var useEffect = wp.element.useEffect;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var PanelBody = wp.components.PanelBody;
    var SelectControl = wp.components.SelectControl;
    var Spinner = wp.components.Spinner;

    registerBlockType("az-slider-ux-pro/slider", {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var sliderId = attributes.sliderId || 0;

            var slidersState = useState([]);
            var sliders = slidersState[0];
            var setSliders = slidersState[1];

            var loadingState = useState(true);
            var loading = loadingState[0];
            var setLoading = loadingState[1];

            useEffect(function () {
                wp.apiFetch({ path: "/az-slider-ux-pro/v1/sliders" })
                    .then(function (data) {
                        setSliders(data || []);
                        setLoading(false);
                    })
                    .catch(function () {
                        setLoading(false);
                    });
            }, []);

            var options = [{ label: "-- Chọn Slider --", value: 0 }];
            sliders.forEach(function (s) {
                options.push({ label: s.title + " (ID: " + s.id + ")", value: s.id });
            });

            return [
                createElement(
                    InspectorControls,
                    { key: "inspector" },
                    createElement(
                        PanelBody,
                        { title: "Cài Đặt Slider", initialOpen: true },
                        createElement(SelectControl, {
                            label: "Chọn Az Slider",
                            value: sliderId,
                            options: options,
                            onChange: function (val) {
                                setAttributes({ sliderId: parseInt(val, 10) || 0 });
                            }
                        })
                    )
                ),
                createElement(
                    "div",
                    { className: "azsux-gutenberg-block-preview", key: "preview" },
                    loading
                        ? createElement(Spinner)
                        : sliderId > 0
                        ? createElement("div", { className: "azsux-block-info" }, "Az Slider UX Pro [ID: " + sliderId + "]")
                        : createElement("div", { className: "azsux-block-placeholder" }, "Vui lòng chọn Slider từ bảng Cài Đặt bên phải.")
                )
            ];
        },

        save: function () {
            return null; // Dynamic render on PHP side
        }
    });
})(window.wp);