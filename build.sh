#!/bin/bash
shopt -s nullglob

ENVIRONMENTS=( ansible/hosts/* )
ENVIRONMENTS=( "${ENVIRONMENTS[@]##*/}" )

show_usage() {
  echo "Usage: provision <environment> [options]

<environment> is the environment to provision to ("staging", "production", etc)
[options] is any number of parameters that will be passed to ansible-playbook

Available environments:
`( IFS=$'\n'; echo "${ENVIRONMENTS[*]}" )`

Examples:
  build provision staging
  build provision production
  build provision staging -vv -T 60
  build deploy staging
  build deploy production
  build deploy staging -vv -T 60
"
}

[[ $# -lt 1 ]] && { show_usage; exit 127; }

for arg
do
  [[ $arg = -h ]] && { show_usage; exit 0; }
done

ACTION="$1"; shift
ENV="$1"; shift
EXTRA_PARAMS=$@
HOSTS_FILE="hosts/$ENV"
PASSWORD_FILE=vpwd
PROVISION_CMD="ansible-playbook --vault-password-file $PASSWORD_FILE $ACTION.yml -i $HOSTS_FILE -e env=$ENV $EXTRA_PARAMS"

cd ./ansible

if [[ ! -e $HOSTS_FILE ]]; then
  echo "Error: $ENV is not a valid environment ($HOSTS_FILE does not exist)."
  echo
  echo "Available environments:"
  ( IFS=$'\n'; echo "${ENVIRONMENTS[*]}" )
  exit 1
fi

$PROVISION_CMD
