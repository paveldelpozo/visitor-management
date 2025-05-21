#!/bin/bash

# Lanza Laravel y Vite en paralelo (requiere npm-run-all)
# npm install --save-dev npm-run-all

npx npm-run-all --parallel serve vite
