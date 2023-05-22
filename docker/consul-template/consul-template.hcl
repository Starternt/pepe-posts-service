consul {
  address = "consul:8500"
  retry {
    enabled = true
    attempts = 0
    backoff = "250ms"
    max_backoff = "1m"
  }
}

template {
  source      = "/config/default.conf.ctmpl"
  destination = "/config/default.conf"
}
