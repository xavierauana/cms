<?php

function getActiveThemePath(): string {
    return (config("cms.theme_directory"))(config("theme.active"));
}