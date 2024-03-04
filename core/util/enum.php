<?php
namespace core\util;

enum ArgumentType {
    case Static;
    case Dynamic;
}

enum AuthRedirectType {
    case Auth;
    case NoAuth;
}