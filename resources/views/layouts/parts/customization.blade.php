@php
    $settings = app(app\Settings\CustomizationSettings::class);

    $custom_js_filename = $settings->custom_js_filename;

    $primarycolor = $settings->primary_color;
    $secondarycolor = $settings->secondary_color;
    $tertiarycolor = $settings->tertiary_color;
    $textcolor = $settings->text_color;

    $primarycolorrgb = $settings->convert_hex_to_rgb($settings->primary_color);
    $secondarycolorrgb = $settings->convert_hex_to_rgb($settings->secondary_color);
    $tertiarycolorrgb = $settings->convert_hex_to_rgb($settings->tertiary_color);
    $textcolorrgb = $settings->convert_hex_to_rgb($settings->text_color);
@endphp


<style>
    :root {
            @isset($secondarycolor) --bs-white: {{$secondarycolor}};          @endisset
            @isset($secondarycolor) --bs-white: {{$secondarycolor}};          @endisset
            @isset($tertiarycolor) --bs-gray-dark: {{$tertiarycolor}};        @endisset
            @isset($primarycolor)  --bs-gray-100: {{$primarycolor}};          @endisset
            @isset($textcolor)--bs-gray-700: {{$textcolor}};                  @endisset
            @isset($tertiarycolor) --bs-gray-800: {{$tertiarycolor}};         @endisset
            @isset($textcolor) --bs-gray-900: {{$textcolor}};                 @endisset
            @isset($tertiarycolor) --bs-primary: {{$tertiarycolor}};          @endisset
            @isset($secondarycolor) --bs-white: {{$secondarycolor}};          @endisset
            @isset($primarycolor) --bs-gray-100: {{$primarycolor}};           @endisset
            @isset($textcolor)--bs-gray-700: {{$textcolor}};                  @endisset
            @isset($tertiarycolor) --bs-gray-800: {{$tertiarycolor}};         @endisset
            @isset($textcolor)--bs-gray-900: {{$textcolor}};                  @endisset
            @isset($secondarycolor) --bs-primary-rgb: {{$primarycolorrgb}};   @endisset
            @isset($secondarycolor) --bs-white-rgb: {{$secondarycolorrgb}};   @endisset
            @isset($primarycolor) --bs-red-100-rgb: {{$primarycolorrgb}};     @endisset
            @isset($primarycolor) --bs-gray-100-rgb: {{$primarycolorrgb}};    @endisset
            @isset($tertiarycolor) --bs-gray-800-rgb: {{$tertiarycolorrgb}};  @endisset
            @isset($secondarycolor) --bs-white-rgb: {{$secondarycolorrgb}};   @endisset
            @isset($primarycolor) --bs-body-bg-rgb: {{$primarycolorrgb}};     @endisset
            @isset($textcolor)  --bs-body-color: {{$textcolor}};              @endisset
            @isset($primarycolor)  --bs-body-bg: {{$primarycolor}};           @endisset
        }

    .w-100px {
        width: 100px !important;
    }
</style>

<!-- FavIcon -->
@if (file_exists(storage_path("app/public/images/".$settings->custom_favicon_filename)) && $settings->custom_favicon_filename != null)
    <link rel="icon" type="image/png" href="{{asset("storage/images/".$settings->custom_favicon_filename)}}" />
@endif

<!-- Custom Headerscripts -->
<script src="{{ asset("js/".$custom_js_filename) }}"></script>
