package main

import "github.com/VividCortex/godaemon"

func StartDaemon() {
	godaemon.MakeDaemon(&godaemon.DaemonAttr{})
}