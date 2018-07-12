<?php

function getActiveThemePath(): string {
    return resource_path(config("cms.theme_directory")) . "/" . config("cms.active_theme");
}