--
-- PostgreSQL database dump
--

-- Dumped from database version 17.2 (Debian 17.2-1.pgdg120+1)
-- Dumped by pg_dump version 17.2 (Debian 17.2-1.pgdg120+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: assign_admin_role(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.assign_admin_role() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF NEW.id_role IS NULL THEN
        NEW.id_role := (SELECT id_role FROM role WHERE rola = 'User' LIMIT 1);
    END IF;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.assign_admin_role() OWNER TO docker;

--
-- Name: count_players_in_club(integer); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.count_players_in_club(club_id_param integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$
DECLARE 
    player_count INT;
BEGIN
    SELECT COUNT(*) INTO player_count FROM players WHERE club_id = club_id_param;
    RETURN player_count;
END;
$$;


ALTER FUNCTION public.count_players_in_club(club_id_param integer) OWNER TO docker;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: address; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.address (
    id_address integer NOT NULL,
    postal_code character varying(10),
    street character varying(255),
    locality character varying(255),
    number character varying(50)
);


ALTER TABLE public.address OWNER TO docker;

--
-- Name: address_id_address_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.address_id_address_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.address_id_address_seq OWNER TO docker;

--
-- Name: address_id_address_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.address_id_address_seq OWNED BY public.address.id_address;


--
-- Name: ages; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.ages (
    id integer NOT NULL,
    value integer,
    age_image_path character varying(255)
);


ALTER TABLE public.ages OWNER TO docker;

--
-- Name: ages_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.ages_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ages_id_seq OWNER TO docker;

--
-- Name: ages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.ages_id_seq OWNED BY public.ages.id;


--
-- Name: clubs; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.clubs (
    id integer NOT NULL,
    name character varying(100),
    logo_image_path character varying(255)
);


ALTER TABLE public.clubs OWNER TO docker;

--
-- Name: clubs_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.clubs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.clubs_id_seq OWNER TO docker;

--
-- Name: clubs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.clubs_id_seq OWNED BY public.clubs.id;


--
-- Name: countries; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.countries (
    id integer NOT NULL,
    name character varying(100),
    flag_image_path character varying(255)
);


ALTER TABLE public.countries OWNER TO docker;

--
-- Name: countries_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.countries_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.countries_id_seq OWNER TO docker;

--
-- Name: countries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.countries_id_seq OWNED BY public.countries.id;


--
-- Name: leagues; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.leagues (
    id integer NOT NULL,
    name character varying(100),
    logo_image_path character varying(255)
);


ALTER TABLE public.leagues OWNER TO docker;

--
-- Name: leagues_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.leagues_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.leagues_id_seq OWNER TO docker;

--
-- Name: leagues_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.leagues_id_seq OWNED BY public.leagues.id;


--
-- Name: players; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.players (
    id integer NOT NULL,
    name character varying(100),
    country_id integer,
    league_id integer,
    club_id integer,
    position_id integer,
    age_id integer,
    shirt_number_id integer
);


ALTER TABLE public.players OWNER TO docker;

--
-- Name: positions; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.positions (
    id integer NOT NULL,
    name character varying(50),
    position_image_path character varying(255)
);


ALTER TABLE public.positions OWNER TO docker;

--
-- Name: shirt_numbers; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.shirt_numbers (
    id integer NOT NULL,
    number integer,
    number_image_path character varying(255)
);


ALTER TABLE public.shirt_numbers OWNER TO docker;

--
-- Name: player_details; Type: VIEW; Schema: public; Owner: docker
--

CREATE VIEW public.player_details AS
 SELECT p.id AS player_id,
    p.name AS player_name,
    c.name AS country,
    l.name AS league,
    cl.name AS club,
    pos.name AS "position",
    a.value AS age,
    sn.number AS shirt_number
   FROM ((((((public.players p
     JOIN public.countries c ON ((p.country_id = c.id)))
     JOIN public.leagues l ON ((p.league_id = l.id)))
     JOIN public.clubs cl ON ((p.club_id = cl.id)))
     JOIN public.positions pos ON ((p.position_id = pos.id)))
     JOIN public.ages a ON ((p.age_id = a.id)))
     JOIN public.shirt_numbers sn ON ((p.shirt_number_id = sn.id)));


ALTER VIEW public.player_details OWNER TO docker;

--
-- Name: players_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.players_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.players_id_seq OWNER TO docker;

--
-- Name: players_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.players_id_seq OWNED BY public.players.id;


--
-- Name: positions_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.positions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.positions_id_seq OWNER TO docker;

--
-- Name: positions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.positions_id_seq OWNED BY public.positions.id;


--
-- Name: role; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.role (
    id_role integer NOT NULL,
    rola character varying(50)
);


ALTER TABLE public.role OWNER TO docker;

--
-- Name: role_id_role_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.role_id_role_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.role_id_role_seq OWNER TO docker;

--
-- Name: role_id_role_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.role_id_role_seq OWNED BY public.role.id_role;


--
-- Name: shirt_numbers_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.shirt_numbers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.shirt_numbers_id_seq OWNER TO docker;

--
-- Name: shirt_numbers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.shirt_numbers_id_seq OWNED BY public.shirt_numbers.id;


--
-- Name: transfer; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.transfer (
    id integer NOT NULL,
    player_id integer NOT NULL,
    from_club_id integer NOT NULL,
    to_club_id integer NOT NULL,
    transfer_amount numeric(10,2) NOT NULL
);


ALTER TABLE public.transfer OWNER TO docker;

--
-- Name: transfer_history; Type: VIEW; Schema: public; Owner: docker
--

CREATE VIEW public.transfer_history AS
 SELECT t.id AS transfer_id,
    p.name AS player_name,
    from_club.name AS from_club,
    to_club.name AS to_club,
    t.transfer_amount AS amount
   FROM (((public.transfer t
     JOIN public.players p ON ((t.player_id = p.id)))
     JOIN public.clubs from_club ON ((t.from_club_id = from_club.id)))
     JOIN public.clubs to_club ON ((t.to_club_id = to_club.id)));


ALTER VIEW public.transfer_history OWNER TO docker;

--
-- Name: transfer_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.transfer_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transfer_id_seq OWNER TO docker;

--
-- Name: transfer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.transfer_id_seq OWNED BY public.transfer.id;


--
-- Name: transfer_question_of_the_day; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.transfer_question_of_the_day (
    id integer NOT NULL,
    transfer_id integer NOT NULL,
    question_date date NOT NULL
);


ALTER TABLE public.transfer_question_of_the_day OWNER TO docker;

--
-- Name: transfer_question_of_the_day_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.transfer_question_of_the_day_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transfer_question_of_the_day_id_seq OWNER TO docker;

--
-- Name: transfer_question_of_the_day_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.transfer_question_of_the_day_id_seq OWNED BY public.transfer_question_of_the_day.id;


--
-- Name: user_account; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.user_account (
    id_user integer NOT NULL,
    id_user_details integer,
    id_role integer,
    email character varying(255) NOT NULL,
    login character varying(50) NOT NULL,
    password character varying(255) NOT NULL,
    enabled boolean DEFAULT true NOT NULL,
    salt character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.user_account OWNER TO docker;

--
-- Name: user_account_id_user_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.user_account_id_user_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_account_id_user_seq OWNER TO docker;

--
-- Name: user_account_id_user_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.user_account_id_user_seq OWNED BY public.user_account.id_user;


--
-- Name: user_details; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.user_details (
    id_user_details integer NOT NULL,
    id_address integer,
    name character varying(255),
    surname character varying(255),
    phone character varying(15)
);


ALTER TABLE public.user_details OWNER TO docker;

--
-- Name: user_details_id_user_details_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.user_details_id_user_details_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_details_id_user_details_seq OWNER TO docker;

--
-- Name: user_details_id_user_details_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.user_details_id_user_details_seq OWNED BY public.user_details.id_user_details;


--
-- Name: user_guess_log; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.user_guess_log (
    id integer NOT NULL,
    id_user integer,
    guess_date date NOT NULL,
    guess_number integer,
    guessed_correctly boolean DEFAULT false
);


ALTER TABLE public.user_guess_log OWNER TO docker;

--
-- Name: user_guess_log_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.user_guess_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_guess_log_id_seq OWNER TO docker;

--
-- Name: user_guess_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.user_guess_log_id_seq OWNED BY public.user_guess_log.id;


--
-- Name: user_guess_log_transfer; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.user_guess_log_transfer (
    id integer NOT NULL,
    id_user integer,
    guess_date date NOT NULL,
    guess_number integer,
    guessed_correctly boolean DEFAULT false
);


ALTER TABLE public.user_guess_log_transfer OWNER TO docker;

--
-- Name: user_guess_log_transfer_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.user_guess_log_transfer_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_guess_log_transfer_id_seq OWNER TO docker;

--
-- Name: user_guess_log_transfer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.user_guess_log_transfer_id_seq OWNED BY public.user_guess_log_transfer.id;


--
-- Name: user_player_assignment; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.user_player_assignment (
    id integer NOT NULL,
    user_id integer NOT NULL,
    player_id integer NOT NULL,
    assignment_date date NOT NULL
);


ALTER TABLE public.user_player_assignment OWNER TO docker;

--
-- Name: user_player_assignment_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.user_player_assignment_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_player_assignment_id_seq OWNER TO docker;

--
-- Name: user_player_assignment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.user_player_assignment_id_seq OWNED BY public.user_player_assignment.id;


--
-- Name: address id_address; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.address ALTER COLUMN id_address SET DEFAULT nextval('public.address_id_address_seq'::regclass);


--
-- Name: ages id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.ages ALTER COLUMN id SET DEFAULT nextval('public.ages_id_seq'::regclass);


--
-- Name: clubs id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.clubs ALTER COLUMN id SET DEFAULT nextval('public.clubs_id_seq'::regclass);


--
-- Name: countries id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.countries ALTER COLUMN id SET DEFAULT nextval('public.countries_id_seq'::regclass);


--
-- Name: leagues id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.leagues ALTER COLUMN id SET DEFAULT nextval('public.leagues_id_seq'::regclass);


--
-- Name: players id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.players ALTER COLUMN id SET DEFAULT nextval('public.players_id_seq'::regclass);


--
-- Name: positions id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.positions ALTER COLUMN id SET DEFAULT nextval('public.positions_id_seq'::regclass);


--
-- Name: role id_role; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.role ALTER COLUMN id_role SET DEFAULT nextval('public.role_id_role_seq'::regclass);


--
-- Name: shirt_numbers id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.shirt_numbers ALTER COLUMN id SET DEFAULT nextval('public.shirt_numbers_id_seq'::regclass);


--
-- Name: transfer id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.transfer ALTER COLUMN id SET DEFAULT nextval('public.transfer_id_seq'::regclass);


--
-- Name: transfer_question_of_the_day id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.transfer_question_of_the_day ALTER COLUMN id SET DEFAULT nextval('public.transfer_question_of_the_day_id_seq'::regclass);


--
-- Name: user_account id_user; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_account ALTER COLUMN id_user SET DEFAULT nextval('public.user_account_id_user_seq'::regclass);


--
-- Name: user_details id_user_details; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_details ALTER COLUMN id_user_details SET DEFAULT nextval('public.user_details_id_user_details_seq'::regclass);


--
-- Name: user_guess_log id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_guess_log ALTER COLUMN id SET DEFAULT nextval('public.user_guess_log_id_seq'::regclass);


--
-- Name: user_guess_log_transfer id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_guess_log_transfer ALTER COLUMN id SET DEFAULT nextval('public.user_guess_log_transfer_id_seq'::regclass);


--
-- Name: user_player_assignment id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_player_assignment ALTER COLUMN id SET DEFAULT nextval('public.user_player_assignment_id_seq'::regclass);


--
-- Data for Name: address; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.address (id_address, postal_code, street, locality, number) FROM stdin;
14	00000	Nieznana	Nieznana	0
15	00000	Nieznana	Nieznana	0
16	00000	Nieznana	Nieznana	0
17	00000	Nieznana	Nieznana	0
18	00000	Nieznana	Nieznana	0
19	00000	Nieznana	Nieznana	0
20	00000	Nieznana	Nieznana	0
21	00000	Nieznana	Nieznana	0
22	00000	Nieznana	Nieznana	0
23	00000	Nieznana	Nieznana	0
24	00000	Nieznana	Nieznana	0
25	00000	Nieznana	Nieznana	0
26	00000	Nieznana	Nieznana	0
27	00000	Nieznana	Nieznana	0
28	00000	Nieznana	Nieznana	0
29	00000	Nieznana	Nieznana	0
30	00000	Nieznana	Nieznana	0
31	00000	Nieznana	Nieznana	0
\.


--
-- Data for Name: ages; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.ages (id, value, age_image_path) FROM stdin;
3	3	3
2	2	2
1	1	1
13	13	13
12	12	12
11	11	11
10	10	10
9	9	9
8	8	8
7	7	7
6	6	6
5	5	5
4	4	4
20	20	20\n
19	19	19
18	18	18
17	17	17
16	16	16
15	15	15
14	14	14
37	37	37
36	36	36
35	35	35
34	34	34
33	33	33
32	32	32
31	31	31
30	30	30
29	29	29
28	28	28
27	27	27
26	26	26
25	25	25
24	24	24
23	23	23
22	22	22
21	21	21
44	44	44
43	43	43
42	42	42
41	41	41
40	40	40
39	39	39
38	38	38
50	50	50
49	49	49
48	48	48
47	47	47
46	46	46
45	45	45
\.


--
-- Data for Name: clubs; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.clubs (id, name, logo_image_path) FROM stdin;
1	Arsenal	Arsenal
2	Manchester City	Manchester City
3	Chelsea	Chelsea
4	Liverpool	Liverpool
5	Tottenham Hotspur	Tottenham Hotspur
6	Manchester United	Manchester United
7	Newcastle United	Newcastle United
8	Aston Villa	Aston Villa
9	Brighton & Hove Albion 	Brighton & Hove Albion 
10	West Ham United	West Ham United
11	Nottingham Forest 	Nottingham Forest 
12	FC Brentford 	FC Brentford 
13	Crystal Palace	Crystal Palace
14	Wolverhampton Wanderers	Wolverhampton Wanderers
15	AFC Bournemouth	AFC Bournemouth
16	FC Fulham 	FC Fulham 
17	Everton	Everton
18	FC Southampton 	FC Southampton 
19	Leicester City 	Leicester City 
20	Ipswich Town 	Ipswich Town 
21	FC Paris Saint-Germain	FC Paris Saint-Germain
22	Monaco	Monaco
23	LOSC Lille	LOSC Lille
24	Olympique Marseille	Olympique Marseille
25	Olympique Lyon	Olympique Lyon
26	OGC Nice	OGC Nice
27	RC Strasbourg Alsace	RC Strasbourg Alsace
28	Stade Rennais FC	Stade Rennais FC
29	RC Lens	RC Lens
30	Stade Reims	Stade Reims
31	Stade Brestois 29	Stade Brestois 29
32	FC Nantes	FC Nantes
33	FC Toulouse	FC Toulouse
34	Montpellier HSC	Montpellier HSC
35	Le Havre AC	Le Havre AC
36	AJ Auxerre 	AJ Auxerre 
37	AS Saint-├ëtienne	AS Saint-├ëtienne
38	Angers SCO	Angers SCO
39	Inter Mediolan	Inter Mediolan
40	Juventus Turyn	Juventus Turyn
41	AC Milan	AC Milan
42	Atalanta BC	Atalanta BC
43	SSC Napoli 	SSC Napoli 
44	AS Roma	AS Roma
45	Lazio Rzym	Lazio Rzym
46	AC Fiorentina	AC Fiorentina
47	FC Bologna	FC Bologna
48	Torino FC	Torino FC
49	Udinese Calcio	Udinese Calcio
50	Parma Calcio 1913	Parma Calcio 1913
51	Genoa CFC	Genoa CFC
52	Como 1907	Como 1907
53	AC Monza	AC Monza
54	US Lecce	US Lecce
55	Hellas Verona	Hellas Verona
56	FC Empoli	FC Empoli
57	Venezia FC	Venezia FC
58	Cagliari Calcio	Cagliari Calcio
59	Real Madryt	Real Madryt
60	FC Barcelona	FC Barcelona
61	Atletico Madryt	Atletico Madryt
62	Real Sociedad	Real Sociedad
63	Athletic Bilbao	Athletic Bilbao
64	Valencia CF	Valencia CF
65	Villarreal CF	Villarreal CF
66	Sevilla FC	Sevilla FC
67	Girona FC	Girona FC
68	Real Betis	Real Betis
69	UD Las Palmas	UD Las Palmas
70	CA Osasuna	CA Osasuna
71	Celta de Vigo	Celta de Vigo
72	RCD Mallorca	RCD Mallorca
73	Deportivo Alaves	Deportivo Alaves
74	RCD Espanyol	RCD Espanyol
75	Getafe CF	Getafe CF
76	Rayo Vallecano	Rayo Vallecano
77	CD Leganes	CD Leganes
78	Real Valladolid CF	Real Valladolid CF
79	Bayern Monachium	Bayern Monachium
80	Bayer 04 Leverkusen	Bayer 04 Leverkusen
81	RB Leipzig	RB Leipzig
82	Borussia Dortmund	Borussia Dortmund
83	VfB Stuttgart	VfB Stuttgart
84	Eintracht Frankfurt	Eintracht Frankfurt
85	VfL Wolfsburg	VfL Wolfsburg
86	SC Freiburg	SC Freiburg
87	TSG 1899 Hoffenheim	TSG 1899 Hoffenheim
88	Borussia Monchengladbach	Borussia Monchengladbach
89	SV Werder Bremen	SV Werder Bremen
90	1.FC Union Berlin	1.FC Union Berlin
91	1.FSV Mainz 05	1.FSV Mainz 05
92	FC Augsburg	FC Augsburg
93	1.FC Heidenheim 1846	1.FC Heidenheim 1846
94	FC St. Pauli	FC St. Pauli
95	VfL Bochum	VfL Bochum
96	Holstein Kiel	Holstein Kiel
\.


--
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.countries (id, name, flag_image_path) FROM stdin;
1	Brazylia	Brazylia
2	Niemcy	Niemcy
3	Norwegia	Norwegia
4	Chorwacja	Chorwacja
5	Polska	Polska
6	Anglia	Anglia
7	Egipt	Egipt
8	Portugalia	Portugalia
9	Czechy	Czechy
10	Holandia	Holandia
11	Francja	Francja
12	Szwecja	Szwecja
13	Nowa Zelandia	Nowa Zelandia
14	Nigeria	Nigeria
15	Kamerun	Kamerun
16	Dania	Dania
17	Senegal	Senegal
18	Szwajcaria	Szwajcaria
19	Kanada	Kanada
20	Belgia	Belgia
21	Jamajka	Jamajka
22	Argentyna	Argentyna
23	Wybrze┼╝e Ko┼Ťci S┼éoniowej	Wybrze┼╝e Ko┼Ťci S┼éoniowej
24	Walia	Walia
25	Australia	Australia
26	Japonia	Japonia
27	Maroko	Maroko
28	Stany Zjednoczone	Stany Zjednoczone
29	Mali	Mali
30	Gwinea	Gwinea
31	Gujana Francuska	Gujana Francuska
32	Demokratyczna Republika Kongo	Demokratyczna Republika Kongo
33	Algieria	Algieria
34	Hiszpania	Hiszpania
35	W┼éochy	W┼éochy
36	S┼éowacja	S┼éowacja
37	Serbia	Serbia
38	S┼éowenia	S┼éowenia
39	Urugwaj	Urugwaj
40	Kolumbia	Kolumbia
41	Austria	Austria
\.


--
-- Data for Name: leagues; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.leagues (id, name, logo_image_path) FROM stdin;
1	Premier League	Premier League
2	Ligue 1	Ligue 1
3	Serie A	Serie A
4	LaLiga	LaLiga
5	Bundesliga	Bundesliga
\.


--
-- Data for Name: players; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.players (id, name, country_id, league_id, club_id, position_id, age_id, shirt_number_id) FROM stdin;
1	Kai Havertz	2	1	1	1	25	29
3	Erling Haaland	3	1	2	1	24	9
4	Josko Gvardiol	4	1	2	3	23	24
6	Cole Palmer	6	1	3	2	22	20
7	Trevoh Chalobah	6	1	3	3	25	23
8	Mohamed Salah	7	1	4	1	32	11
9	Diogo Jota	8	1	4	1	28	20
10	Dominic Solanke	6	1	5	1	27	19
11	Antonin Kinsky	9	1	5	4	21	31
12	Matthijs de Ligt	10	1	6	3	25	4
14	Alexander Isak	12	1	7	1	25	14
15	Jacob Murphy	6	1	7	1	29	23
16	Matty Cash	5	1	8	3	27	2
17	Ross Barkley	6	1	8	2	31	6
19	 Bart Verbruggen	10	1	9	4	22	1
20	Lukasz Fabianski	5	1	10	4	39	1
21	Tomas Soucek	9	1	10	2	29	28
22	Chris Wood	13	1	11	1	33	11
23	Ola Aina	14	1	11	3	28	34
24	Bryan Mbeumo	15	1	12	1	25	19
25	Mikkel Damsgaard	16	1	12	1	24	24
26	Tyrick Mitchell	6	1	13	3	25	3
27	Eberechi Eze	6	1	13	2	26	10
28	Matheus Cunha	1	1	14	1	25	10
29	Goncalo Guedes	8	1	14	1	28	29
30	Justin Kluivert	10	1	15	1	25	19
31	Marcus Tavernier	6	1	15	2	25	16
32	Joachim Andersen	16	1	16	3	28	5
33	Bernd Leno	2	1	16	4	32	1
36	Jan Bednarek	5	1	18	3	28	35
5	Jakub Kiwior	5	1	1	3	24	15
39	Jamie Vardy	6	1	19	1	38	9
40	Liam Delap	6	1	20	1	21	19
42	Bradley Barcola	11	2	21	1	22	29
43	Joao Neves	8	2	21	2	20	87
44	 Radoslaw Majecki	5	2	22	4	25	1
45	Breel Embolo	18	2	22	1	27	36
47	Thomas Meunier	20	2	23	3	33	12
48	Mason Greenwood	21	2	24	1	23	10
50	Alexandre Lacazette	11	2	25	1	33	10
51	Nicolas Tagliafico	22	2	25	3	32	3
52	Marcin Bulka	5	2	26	4	25	1
53	Evann Guessand	23	2	26	1	23	29
54	Mamadou Sarr	11	2	27	3	19	23
55	Emanuel Emegha	10	2	27	1	21	10
57	Jordan James	24	2	28	2	20	17
58	Przemyslaw Frankowski	5	2	29	2	29	29
59	Mathew Ryan	25	2	29	4	32	30
60	Keito Nakamura	26	2	30	1	24	17
61	Aurelio Buta	8	2	30	3	27	23
62	Mahdi Camara	11	2	31	2	26	45
63	Romain Faivre	11	2	31	2	26	21
65	Moses Simon	14	2	32	1	29	27
66	Zakaria Aboukhlal	27	2	33	1	24	7
67	Mark McKenzie	28	2	33	3	25	3
70	Abdoulaye Toure	30	2	35	2	30	94
71	Christopher Operi	23	2	35	3	27	21
72	Hamed Junior Traore	23	2	36	2	24	25
73	Donovan Leon	31	2	36	4	32	16
74	Lucas Stassin	20	2	37	1	20	32
75	Dylan Batubinsika	32	2	37	3	28	21
76	Lilian Raolisoa	11	2	38	3	24	27
77	Himad Abdelli	33	2	38	2	25	10
41	Leif Davis	6	1	20	3	25	3
78	Gabriel Jesus	1	1	1	1	27	9
38	Jakub Stolarczyk	5	1	19	4	24	41
46	Jonathan David	19	2	23	1	25	9
56	Arnaud Kalimuendo	11	2	28	1	23	9
49	Pierre-Emile Hojbjerg	16	2	24	2	29	23
13	Leny Yoro	11	1	6	3	19	15
79	Piotr Zielinski	5	3	39	2	30	7
80	Timothy Weah	28	3	40	1	24	22
81	Christian Pulisic	28	3	41	1	26	11
82	Ademola Lookman	14	3	42	1	27	11
83	Juan Jesus	1	3	43	3	33	5
84	Nicola Zalewski	5	3	44	2	23	59
85	Matteo Guendouzi	11	3	45	2	25	8
86	David de Gea	34	3	46	4	34	43
87	Lukasz Skorupski	5	3	47	4	33	1
88	Karol Linetty	5	3	48	2	29	77
89	Thomas Kristensen	16	3	49	3	23	31
90	Hernani	1	3	50	2	30	27
91	Vitinha	8	3	51	1	24	9
92	Nico Paz	22	3	52	2	20	79
93	Kacper Urbanski	5	3	53	2	20	8
94	Antonino Gallo	35	3	54	3	25	25
95	Ondrej Duda	36	3	55	2	30	33
96	Pietro Pellegri	35	3	56	1	23	9
97	Filip Stankovic	37	3	57	4	22	35
98	Nadir Zortea	35	3	58	2	25	19
99	Kylian Mbappe	11	4	59	1	26	9
100	Robert Lewandowski	5	4	60	1	36	9
101	Jan Oblak	38	4	61	4	32	13
103	Nico Williams	34	4	63	1	22	10
104	Yarek Gasiorowski	34	4	64	3	20	24
105	Thierno Barry	11	4	65	1	22	15
106	Juanlu Sanchez	34	4	66	3	21	26
107	Cristhian Stuani	39	4	67	1	38	7
108	Vitor Roque	1	4	68	1	19	8
2	Gabriel Martinelli	1	1	2	1	23	11
110	Jon Moncayola	34	4	70	2	26	7
109	Jasper Cillessen	10	4	69	4	35	1
111	Iago Aspas	34	4	71	1	37	10
113	Carlos Protesoni	39	4	73	2	26	23
114	Leandro Cabrera	39	4	74	3	33	6
115	Domingos Duarte	8	4	75	3	29	22
116	Jorge de Frutos	34	4	76	1	27	19
117	Oscar Rodriguez	34	4	77	2	26	7
119	Luis Perez	34	4	78	3	29	2
120	Jamal Musiala	2	5	79	2	21	42
121	Florian Wirtz	2	5	80	2	21	10
122	Arthur Vermeeren	20	5	81	2	19	18
123	Julian Brandt	2	5	82	2	28	10
124	Angelo Stiller	2	5	83	2	23	6
125	Mario Gotze	2	5	84	2	32	27
126	Kamil Grabara	5	5	85	4	26	1
127	Vincenzo Grifo	35	5	86	1	31	32
128	Andrej Kramaric	4	5	87	1	33	27
129	Tim Kleindienst	2	5	88	1	29	11
130	Marvin Ducksch	2	5	89	1	30	7
131	Alexander Schwolow	2	5	90	4	32	37
132	Stefan Bell	2	5	91	3	33	16
133	Phillip Tietz	2	5	92	1	27	21
134	Marvin Pieringer	2	5	93	1	25	18
135	David Nemeth	41	5	94	3	23	4
136	Philipp Hofmann	2	5	95	1	31	33
137	Timon Weiner	2	5	96	4	26	1
18	Joao Pedro	1	1	9	1	23	9
34	Iliman Ndiaye	17	1	17	1	24	10
35	Jordan Pickford	6	1	17	4	30	1
37	Adam Lallana	6	1	18	2	36	10
64	Anthony Lopes	8	2	32	4	34	16
68	Joris Chotard	11	2	34	2	23	13
69	Kiki Kouyate	29	2	34	3	27	4
102	Aritz Elustondo	34	4	62	3	30	6
112	Johan Mojica	40	4	72	3	32	22
\.


--
-- Data for Name: positions; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.positions (id, name, position_image_path) FROM stdin;
1	N	N
2	POM	POM
3	OBR	OBR
4	BR	BR
\.


--
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.role (id_role, rola) FROM stdin;
1	User
2	Admin
\.


--
-- Data for Name: shirt_numbers; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.shirt_numbers (id, number, number_image_path) FROM stdin;
1	1	path_to_image_1.jpg
2	2	path_to_image_2.jpg
3	3	path_to_image_3.jpg
4	4	path_to_image_4.jpg
5	5	path_to_image_5.jpg
6	6	path_to_image_6.jpg
7	7	path_to_image_7.jpg
8	8	path_to_image_8.jpg
9	9	path_to_image_9.jpg
10	10	path_to_image_10.jpg
11	11	path_to_image_11.jpg
12	12	path_to_image_12.jpg
13	13	path_to_image_13.jpg
14	14	path_to_image_14.jpg
15	15	path_to_image_15.jpg
16	16	path_to_image_16.jpg
17	17	path_to_image_17.jpg
18	18	path_to_image_18.jpg
19	19	path_to_image_19.jpg
20	20	path_to_image_20.jpg
21	21	path_to_image_21.jpg
22	22	path_to_image_22.jpg
23	23	path_to_image_23.jpg
24	24	path_to_image_24.jpg
25	25	path_to_image_25.jpg
26	26	path_to_image_26.jpg
27	27	path_to_image_27.jpg
28	28	path_to_image_28.jpg
29	29	path_to_image_29.jpg
30	30	path_to_image_30.jpg
31	31	path_to_image_31.jpg
32	32	path_to_image_32.jpg
33	33	path_to_image_33.jpg
34	34	path_to_image_34.jpg
35	35	path_to_image_35.jpg
36	36	path_to_image_36.jpg
37	37	path_to_image_37.jpg
38	38	path_to_image_38.jpg
39	39	path_to_image_39.jpg
40	40	path_to_image_40.jpg
41	41	path_to_image_41.jpg
42	42	path_to_image_42.jpg
43	43	path_to_image_43.jpg
44	44	path_to_image_44.jpg
45	45	path_to_image_45.jpg
46	46	path_to_image_46.jpg
47	47	path_to_image_47.jpg
48	48	path_to_image_48.jpg
49	49	path_to_image_49.jpg
50	50	path_to_image_50.jpg
51	51	path_to_image_51.jpg
52	52	path_to_image_52.jpg
53	53	path_to_image_53.jpg
54	54	path_to_image_54.jpg
55	55	path_to_image_55.jpg
56	56	path_to_image_56.jpg
57	57	path_to_image_57.jpg
58	58	path_to_image_58.jpg
59	59	path_to_image_59.jpg
60	60	path_to_image_60.jpg
61	61	path_to_image_61.jpg
62	62	path_to_image_62.jpg
63	63	path_to_image_63.jpg
64	64	path_to_image_64.jpg
65	65	path_to_image_65.jpg
66	66	path_to_image_66.jpg
67	67	path_to_image_67.jpg
68	68	path_to_image_68.jpg
69	69	path_to_image_69.jpg
70	70	path_to_image_70.jpg
71	71	path_to_image_71.jpg
72	72	path_to_image_72.jpg
73	73	path_to_image_73.jpg
74	74	path_to_image_74.jpg
75	75	path_to_image_75.jpg
76	76	path_to_image_76.jpg
77	77	path_to_image_77.jpg
78	78	path_to_image_78.jpg
79	79	path_to_image_79.jpg
80	80	path_to_image_80.jpg
81	81	path_to_image_81.jpg
82	82	path_to_image_82.jpg
83	83	path_to_image_83.jpg
84	84	path_to_image_84.jpg
85	85	path_to_image_85.jpg
86	86	path_to_image_86.jpg
87	87	path_to_image_87.jpg
88	88	path_to_image_88.jpg
89	89	path_to_image_89.jpg
90	90	path_to_image_90.jpg
91	91	path_to_image_91.jpg
92	92	path_to_image_92.jpg
93	93	path_to_image_93.jpg
94	94	path_to_image_94.jpg
95	95	path_to_image_95.jpg
96	96	path_to_image_96.jpg
97	97	path_to_image_97.jpg
98	98	path_to_image_98.jpg
99	99	path_to_image_99.jpg
100	100	path_to_image_100.jpg
\.


--
-- Data for Name: transfer; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.transfer (id, player_id, from_club_id, to_club_id, transfer_amount) FROM stdin;
1	1	3	1	75.00
2	78	2	1	52.20
4	2	1	2	100.00
5	6	2	3	47.00
\.


--
-- Data for Name: transfer_question_of_the_day; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.transfer_question_of_the_day (id, transfer_id, question_date) FROM stdin;
1	1	2025-01-27
2	1	2025-01-28
3	1	2025-01-28
4	1	2025-01-29
5	2	2025-01-30
15	5	2025-02-01
14	5	2025-01-31
\.


--
-- Data for Name: user_account; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.user_account (id_user, id_user_details, id_role, email, login, password, enabled, salt, created_at) FROM stdin;
31	30	1	ss@ss.pl	Jaca67	$2y$10$znqaz5LM57x/hUOXpbW4wevyRD2Fcbqu90LSBPKIFxO2f8BPkKLj2	t	\N	2025-01-25 19:38:49.403838
32	31	1	bananek100@gmail.com	Bananek101	$2y$10$H2jMQAuh1xT5ShaKMNYUAu8QCZhtCrPAQhCNxtjs/Qiv12KdQNoTW	t	\N	2025-01-30 17:39:16.706445
29	28	2	barabara205@marchewka.pl	Kacper Mor	$2y$10$t.u/yJBrTsGGNeXaMO7IHO3H8OTA2rDRnDpanluOXu94GbCxC02DK	t	\N	2025-01-25 18:54:09.396037
15	14	1	s@s	w	$2y$10$nd3UADhCHW.9P43Sb3HTvuMQTR2N5yuzo73jGPE37Dh/N7nsyTMFq	t	\N	2025-01-02 00:16:02.376328
19	18	1	kacper@kot.pl	Mor─ů	$2y$10$JT64VjXWYL0eohogBDS3q.H6b72OhxD2kH4XZLrX5Ao9CqlAOZxdy	t	\N	2025-01-02 01:17:05.953063
20	19	1	kacper@kotek.pl	Mordacz2137	$2y$10$a4LjBAhplvr4FbZlzUkWZuwVMOiYQrMDNax3RuQO4Gk9ZTmQfEodm	t	\N	2025-01-02 01:24:55.647488
22	21	1	dinoazaur@123.com	Kasztan	$2y$10$DeA/Ohb6EtKJAE8wL/wbJ.fF4SnueuTIbij2Ha0TatmStnRLNGKva	t	\N	2025-01-02 22:47:41.709191
24	23	1	k@c.pl	Pantofel	$2y$10$jcqV.lqDnkmjJM7.7ZFAk.t.Z281NNYR8S/9l5wLR13aT/xa1Svy.	t	\N	2025-01-02 23:36:07.985
28	27	1	macius12@lok.pl	macius16	$2y$10$dfF5n7qadIlJ.QnNEgD4BO4AxHqTvUbyGeqwNTwKAy8vnfpKfy/CO	t	\N	2025-01-21 15:40:56.011691
30	29	1	dwd@dwdw.pl	bla bla bla 21	$2y$10$zqHZ6aG2Lu/LP5n7BB2xSOYhYz5LupLbgGNZ0f2hVhll6YM2nGUtW	t	\N	2025-01-25 18:56:15.739804
25	24	1	piotr@ogorek.pl	macius123	$2y$10$wjZdExgcKQHX0JRW4fcjJ.0zAwVZaRYqFBD7Y5On4I6O3vVqR6a/S	t	\N	2025-01-16 19:08:41.761953
\.


--
-- Data for Name: user_details; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.user_details (id_user_details, id_address, name, surname, phone) FROM stdin;
14	14	w	Nieznane	2312
15	15	Mordacz	Nieznane	664898797
16	16	sigma	Nieznane	6202323
17	17	666	Nieznane	666666666
18	18	Mor─ů	Nieznane	123456789
19	19	Mordacz2137	Nieznane	123456700
20	20	Mordaczos21	Nieznane	664664664
21	21	Kasztan	Nieznane	567890876
22	22	Fiufiu	Nieznane	123123129
23	23	Pantofel	Nieznane	567897294
24	24	macius12	Nieznane	666565920
25	25	macius13	Nieznane	123456123
26	26	macius14	Nieznane	154678920
27	27	macius16	Nieznane	123536745
28	28	Kacper M	Nieznane	664309666
29	29	bla bla bla 21	Nieznane	456890001
30	30	Jaca670	Nieznane	657849001
31	31	Bananek100	Nieznane	567879543
\.


--
-- Data for Name: user_guess_log; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.user_guess_log (id, id_user, guess_date, guess_number, guessed_correctly) FROM stdin;
37	29	2025-01-28	1	t
32	29	2025-01-27	5	f
86	30	2025-01-31	4	t
40	29	2025-01-29	5	f
27	31	2025-01-25	3	f
35	30	2025-01-27	2	t
29	30	2025-01-26	1	f
39	30	2025-01-29	4	t
82	25	2025-01-30	1	f
28	29	2025-01-26	1	f
17	25	2025-01-23	2	t
4	25	2025-01-21	5	t
18	25	2025-01-24	1	t
81	30	2025-01-30	0	f
83	31	2025-01-30	1	f
21	25	2025-01-25	2	t
30	31	2025-01-26	0	f
24	25	2025-01-26	2	t
33	31	2025-01-27	5	t
34	25	2025-01-27	5	t
80	29	2025-01-30	5	t
79	25	2025-01-29	3	f
38	25	2025-01-28	3	f
84	29	2025-01-31	5	t
26	29	2025-01-25	4	t
25	30	2025-01-25	5	t
85	32	2025-01-31	4	t
\.


--
-- Data for Name: user_guess_log_transfer; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.user_guess_log_transfer (id, id_user, guess_date, guess_number, guessed_correctly) FROM stdin;
36	25	2025-01-11	2	t
27	29	2025-01-27	3	t
37	25	2025-01-12	2	t
38	25	2025-01-13	1	t
39	25	2025-01-14	3	t
41	25	2025-01-30	1	f
42	31	2025-01-30	5	t
40	29	2025-01-30	2	t
29	30	2025-01-27	5	f
30	31	2025-01-27	2	t
43	29	2025-01-31	1	t
44	32	2025-01-31	1	t
31	31	2025-01-28	5	f
28	25	2025-01-27	2	t
32	29	2025-01-28	1	t
45	30	2025-01-31	2	t
33	30	2025-01-29	5	t
34	29	2025-01-29	2	t
35	31	2025-01-29	1	t
\.


--
-- Data for Name: user_player_assignment; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.user_player_assignment (id, user_id, player_id, assignment_date) FROM stdin;
1	29	2	2025-01-26
2	30	2	2025-01-26
3	31	2	2025-01-26
5	25	1	2025-01-26
6	29	1	2025-01-27
7	31	2	2025-01-27
8	25	1	2025-01-27
9	30	2	2025-01-27
11	29	10	2025-01-28
12	30	39	2025-01-28
13	31	33	2025-01-28
14	25	23	2025-01-28
15	30	21	2025-01-29
16	29	10	2025-01-29
17	31	31	2025-01-29
19	25	41	2025-01-29
20	29	25	2025-01-30
27	30	56	2025-01-30
28	31	13	2025-01-30
30	25	4	2025-01-30
31	32	67	2025-01-30
33	29	80	2025-01-31
34	32	131	2025-01-31
35	30	52	2025-01-31
\.


--
-- Name: address_id_address_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.address_id_address_seq', 31, true);


--
-- Name: ages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.ages_id_seq', 1, true);


--
-- Name: clubs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.clubs_id_seq', 1, true);


--
-- Name: countries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.countries_id_seq', 1, false);


--
-- Name: leagues_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.leagues_id_seq', 1, false);


--
-- Name: players_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.players_id_seq', 11, true);


--
-- Name: positions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.positions_id_seq', 1, false);


--
-- Name: role_id_role_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.role_id_role_seq', 1, false);


--
-- Name: shirt_numbers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.shirt_numbers_id_seq', 100, true);


--
-- Name: transfer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.transfer_id_seq', 5, true);


--
-- Name: transfer_question_of_the_day_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.transfer_question_of_the_day_id_seq', 15, true);


--
-- Name: user_account_id_user_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.user_account_id_user_seq', 33, true);


--
-- Name: user_details_id_user_details_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.user_details_id_user_details_seq', 31, true);


--
-- Name: user_guess_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.user_guess_log_id_seq', 86, true);


--
-- Name: user_guess_log_transfer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.user_guess_log_transfer_id_seq', 45, true);


--
-- Name: user_player_assignment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.user_player_assignment_id_seq', 35, true);


--
-- Name: address address_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.address
    ADD CONSTRAINT address_pkey PRIMARY KEY (id_address);


--
-- Name: ages ages_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.ages
    ADD CONSTRAINT ages_pkey PRIMARY KEY (id);


--
-- Name: clubs clubs_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.clubs
    ADD CONSTRAINT clubs_pkey PRIMARY KEY (id);


--
-- Name: countries countries_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.countries
    ADD CONSTRAINT countries_pkey PRIMARY KEY (id);


--
-- Name: leagues leagues_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.leagues
    ADD CONSTRAINT leagues_pkey PRIMARY KEY (id);


--
-- Name: players players_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.players
    ADD CONSTRAINT players_pkey PRIMARY KEY (id);


--
-- Name: positions positions_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.positions
    ADD CONSTRAINT positions_pkey PRIMARY KEY (id);


--
-- Name: role role_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id_role);


--
-- Name: shirt_numbers shirt_numbers_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.shirt_numbers
    ADD CONSTRAINT shirt_numbers_pkey PRIMARY KEY (id);


--
-- Name: transfer transfer_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.transfer
    ADD CONSTRAINT transfer_pkey PRIMARY KEY (id);


--
-- Name: transfer_question_of_the_day transfer_question_of_the_day_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.transfer_question_of_the_day
    ADD CONSTRAINT transfer_question_of_the_day_pkey PRIMARY KEY (id);


--
-- Name: user_account user_account_email_key; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_account
    ADD CONSTRAINT user_account_email_key UNIQUE (email);


--
-- Name: user_account user_account_login_key; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_account
    ADD CONSTRAINT user_account_login_key UNIQUE (login);


--
-- Name: user_account user_account_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_account
    ADD CONSTRAINT user_account_pkey PRIMARY KEY (id_user);


--
-- Name: user_details user_details_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_details
    ADD CONSTRAINT user_details_pkey PRIMARY KEY (id_user_details);


--
-- Name: user_guess_log user_guess_log_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_guess_log
    ADD CONSTRAINT user_guess_log_pkey PRIMARY KEY (id);


--
-- Name: user_guess_log_transfer user_guess_log_transfer_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_guess_log_transfer
    ADD CONSTRAINT user_guess_log_transfer_pkey PRIMARY KEY (id);


--
-- Name: user_player_assignment user_player_assignment_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_player_assignment
    ADD CONSTRAINT user_player_assignment_pkey PRIMARY KEY (id);


--
-- Name: user_player_assignment user_player_assignment_user_id_assignment_date_key; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_player_assignment
    ADD CONSTRAINT user_player_assignment_user_id_assignment_date_key UNIQUE (user_id, assignment_date);


--
-- Name: user_account assign_admin_role; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER assign_admin_role BEFORE INSERT ON public.user_account FOR EACH ROW EXECUTE FUNCTION public.assign_admin_role();


--
-- Name: user_account set_admin_role; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER set_admin_role BEFORE INSERT ON public.user_account FOR EACH ROW EXECUTE FUNCTION public.assign_admin_role();


--
-- Name: players players_age_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.players
    ADD CONSTRAINT players_age_id_fkey FOREIGN KEY (age_id) REFERENCES public.ages(id);


--
-- Name: players players_club_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.players
    ADD CONSTRAINT players_club_id_fkey FOREIGN KEY (club_id) REFERENCES public.clubs(id);


--
-- Name: players players_country_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.players
    ADD CONSTRAINT players_country_id_fkey FOREIGN KEY (country_id) REFERENCES public.countries(id);


--
-- Name: players players_league_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.players
    ADD CONSTRAINT players_league_id_fkey FOREIGN KEY (league_id) REFERENCES public.leagues(id);


--
-- Name: players players_position_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.players
    ADD CONSTRAINT players_position_id_fkey FOREIGN KEY (position_id) REFERENCES public.positions(id);


--
-- Name: players players_shirt_number_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.players
    ADD CONSTRAINT players_shirt_number_id_fkey FOREIGN KEY (shirt_number_id) REFERENCES public.shirt_numbers(id);


--
-- Name: transfer transfer_from_club_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.transfer
    ADD CONSTRAINT transfer_from_club_id_fkey FOREIGN KEY (from_club_id) REFERENCES public.clubs(id) ON DELETE CASCADE;


--
-- Name: transfer transfer_player_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.transfer
    ADD CONSTRAINT transfer_player_id_fkey FOREIGN KEY (player_id) REFERENCES public.players(id) ON DELETE CASCADE;


--
-- Name: transfer_question_of_the_day transfer_question_of_the_day_transfer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.transfer_question_of_the_day
    ADD CONSTRAINT transfer_question_of_the_day_transfer_id_fkey FOREIGN KEY (transfer_id) REFERENCES public.transfer(id) ON DELETE CASCADE;


--
-- Name: transfer transfer_to_club_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.transfer
    ADD CONSTRAINT transfer_to_club_id_fkey FOREIGN KEY (to_club_id) REFERENCES public.clubs(id) ON DELETE CASCADE;


--
-- Name: user_account user_account_id_role_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_account
    ADD CONSTRAINT user_account_id_role_fkey FOREIGN KEY (id_role) REFERENCES public.role(id_role) ON DELETE SET NULL;


--
-- Name: user_account user_account_id_user_details_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_account
    ADD CONSTRAINT user_account_id_user_details_fkey FOREIGN KEY (id_user_details) REFERENCES public.user_details(id_user_details) ON DELETE CASCADE;


--
-- Name: user_details user_details_id_address_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_details
    ADD CONSTRAINT user_details_id_address_fkey FOREIGN KEY (id_address) REFERENCES public.address(id_address) ON DELETE CASCADE;


--
-- Name: user_guess_log user_guess_log_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_guess_log
    ADD CONSTRAINT user_guess_log_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.user_account(id_user) ON DELETE CASCADE;


--
-- Name: user_guess_log_transfer user_guess_log_transfer_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_guess_log_transfer
    ADD CONSTRAINT user_guess_log_transfer_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.user_account(id_user) ON DELETE CASCADE;


--
-- Name: user_player_assignment user_player_assignment_player_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_player_assignment
    ADD CONSTRAINT user_player_assignment_player_id_fkey FOREIGN KEY (player_id) REFERENCES public.players(id);


--
-- Name: user_player_assignment user_player_assignment_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_player_assignment
    ADD CONSTRAINT user_player_assignment_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.user_account(id_user) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

