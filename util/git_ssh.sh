#!/usr/bin/env bash
# Change the git ssh settings.

# The script for git<v2.3.x .
# If you use git>=v2.3.x, I reccomend just use `GIT_SSH_COMMAND=ssh ...`.
# Also, git>=2.10.x has `-c core.sshCommand="ssh ..."` option.

# usage:
# (Place your deploy (private) key in the same directory under the name `deploy.key`.)
# GIT_SSH=/path/to/this/git_ssh.sh git clone git@github.com:your/repo.git
# GIT_SSH=/path/to/this/git_ssh.sh git pull

# abs_dirname code from
# https://github.com/rbenv/rbenv
# https://kohkimakimoto.hatenablog.com/entry/2014/06/12/104110
abs_dirname() {
  local cwd="$(pwd)"
  local path="$1"
  while [ -n "$path" ]; do
    cd "${path%/*}"
    local name="${path##*/}"
    path="$(readlink "$name" || true)"
  done
  pwd -P
  cd "$cwd"
}
script_dir="$(abs_dirname "$0")"

# The script is lack security. because will be use in deploy automation purpose(almost, none tty enviroments).
exec ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -i $script_dir/deploy.key "$@"
