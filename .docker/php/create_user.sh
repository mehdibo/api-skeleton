#!/usr/bin/env bash

if [ -z ${GROUP_ID} ]; then
  echo "Creating group 'user'"
  GROUP_ID=$(addgroup user | grep "GID" | cut -d " " -f 5 | tr -d ")")
  echo "Created group user with id $GROUP_ID"
else
  echo "Creating group 'user' with GID $GROUP_ID"
  addgroup --gid "$GROUP_ID" user
fi

if [ -z ${USER_ID} ]; then
  echo "Creating user 'user'"
  adduser --disabled-password --gecos '' --gid "$GROUP_ID" user
else
  echo "Creating user 'user' with UID $USER_ID"
  adduser --disabled-password --gecos '' --uid "$USER_ID" --gid "$GROUP_ID" user
fi
