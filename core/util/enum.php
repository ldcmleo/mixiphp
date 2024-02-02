<?php

enum ArgumentType {
    case Static;
    case Dynamic;
}

enum QueryType {
    case Select;
    case Insert;
    case Update;
    case Delete;
}