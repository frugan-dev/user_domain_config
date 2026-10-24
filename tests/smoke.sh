#!/usr/bin/env bash
# End-to-end check of the plugin against the stack of docker-compose.yml: logs
# in with several usernames and looks for the product name set by the
# per-domain config files in tests/fixtures. Needs only curl.
#
#   tests/smoke.sh [base-url]
#
# The config is loaded while the login form is processed, before the IMAP
# server answers, so each check holds whether the login succeeds or not.

set -euo pipefail

base=${1:-http://localhost:${ROUNDCUBE_HTTP_PORT:-8080}}
jar=$(mktemp)
trap 'rm -f "$jar"' EXIT
failures=0

# Logs in as $1 and prints the page Roundcube ends up on.
login() {
	local token

	rm -f "$jar"
	token=$(curl -fsS -c "$jar" "$base/?_task=login" |
		sed -n 's/.*name="_token" value="\([^"]*\)".*/\1/p' | head -n 1)
	if [[ -z $token ]]; then
		echo "no request token on the login page of $base" >&2
		return 1
	fi

	# No -f: a failed login is answered with 401 and the login page.
	curl -sSL -b "$jar" -c "$jar" \
		--data-urlencode "_token=$token" \
		--data-urlencode '_task=login' \
		--data-urlencode '_action=login' \
		--data-urlencode '_timezone=UTC' \
		--data-urlencode '_url=' \
		--data-urlencode "_user=$1" \
		--data-urlencode '_pass=secret' \
		"$base/?_task=login"
}

# check <username> <text> <yes|no>: whether the page after login shows text.
check() {
	local page found=no

	page=$(login "$1")
	if ! grep -q '</html>' <<<"$page"; then
		echo "FAIL  $1: no Roundcube page"
		failures=$((failures + 1))
		return
	fi
	grep -qF "$2" <<<"$page" && found=yes

	if [[ $found == "$3" ]]; then
		echo "ok    $1: '$2' shown: $found"
	else
		echo "FAIL  $1: '$2' shown: $found, expected $3"
		failures=$((failures + 1))
	fi
}

check 'alice@example.test' 'Example Test Mail' yes
check 'Alice@EXAMPLE.Test' 'Example Test Mail' yes
check 'bob@other.test' 'Example Test Mail' no
check 'nodomain' 'Example Test Mail' no
check 'x@../outside/trap' 'Trap Loaded' no
check 'x@/var/roundcube/outside/trap' 'Trap Loaded' no

if ((failures)); then
	echo "$failures check(s) failed" >&2
	exit 1
fi
