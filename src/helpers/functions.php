<?php

function getActiveThemePath(): string {
    return resource_path("views/themes/" . config("cms.active_theme"));
}