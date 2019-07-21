--
-- PostgreSQL database dump
--

-- Dumped from database version 10.6 (Ubuntu 10.6-0ubuntu0.18.04.1)
-- Dumped by pg_dump version 10.6 (Ubuntu 10.6-0ubuntu0.18.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'SQL_ASCII';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: WARNING; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."WARNING" (
    id integer NOT NULL,
    warning text,
    bitcoin_address text,
    email text
);


ALTER TABLE public."WARNING" OWNER TO postgres;

--
-- Data for Name: WARNING; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."WARNING" (id, warning, bitcoin_address, email) FROM stdin;
1	To recover your lost Database and avoid leaking it: Send us 0.06 Bitcoin (BTC) to our Bitcoin address 1GH42zWNAoFWVC6UyNut9aJDi2u83sqSeu and contact us by Email with your Server IP or Domain name and a Proof of Payment. If you are unsure if we have your data, contact us and we will send you a proof. Your Database is downloaded and backed up on our servers. Backups that we have right now: authentication, jogadores, times . If we dont receive your payment in the next 10 Days, we will make your database public or use them otherwise.	1GH42zWNAoFWVC6UyNut9aJDi2u83sqSeu	support@mydatabase.to
\.


--
-- PostgreSQL database dump complete
--

