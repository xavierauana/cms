<?php

function getActiveThemePath(): string {
    return resource_path("themes/" . config("cms.active_theme"));
}