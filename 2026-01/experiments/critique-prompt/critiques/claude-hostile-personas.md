# Critique of "Convergence and Compromise: Will Society Aim for Good Futures?"

## Persona: The Mechanism Designer

**Core assumption**: Any proposal without formal specification of equilibrium conditions, strategy spaces, and enforcement mechanisms is philosophical hand-waving that will fail when implemented.

**Steelman**: This paper attempts something genuinely difficult—mapping the conditions under which distributed moral agents might coordinate toward good outcomes despite heterogeneous preferences. The distinction between WAM-convergence and partial AM-convergence with trade is analytically useful, and the recognition that "moral trade" requires specific institutional conditions shows awareness that outcomes depend on mechanism design, not just intentions.

**Signature objection**:
- **Target**: Section 3.1's claim that "superintelligence could enable iron-clad contracts" and that "transaction costs would generally be extremely small relative to the gains"
- **Failure mode**: The paper treats contract enforcement as a solved problem once superintelligence exists, but never specifies *who enforces* contracts between groups with different values, *what constitutes breach* when parties have incommensurable metrics of compliance, or *how disputes are adjudicated* when the adjudicator's values necessarily favor some parties over others. This is the contract theory equivalent of assuming a can opener.
- **Scenario**: In 2150, the Hedonic Maximizers and the Preference Satisfiers sign a resource-sharing agreement. The Hedonists create beings that report high satisfaction but have no preferences in the traditional sense. The Satisfiers claim this violates the agreement's spirit; the Hedonists claim full compliance. There is no neutral arbiter—any superintelligent judge must have *some* theory of welfare to interpret "compliance," and that theory will favor one party. The contract collapses into the very value disagreement it was meant to resolve. Meanwhile, a third party (the Objective List theorists) refuses to recognize the contract's legitimacy at all, since it was negotiated by parties they consider morally confused.

**Author's best reply**: We acknowledge enforcement is non-trivial, but the key insight is that *mutual gains from trade exist* regardless of enforcement mechanism. Parties have incentives to create enforcement institutions because trade benefits them. The specific mechanism matters less than the existence of gains.

**Rebuttal**: This confuses the existence of potential gains with their realizability. The entire literature on mechanism design exists because "mutual gains exist" is insufficient—you need incentive-compatible mechanisms that make cooperation individually rational at every decision node. The paper's optimism about trade rests on assuming these mechanisms exist without specifying them. Worse, when values are the *object* of trade rather than preferences over goods, standard results about efficient mechanisms may not apply—there's no analog to the Myerson-Satterthwaite theorem for moral trade, and the paper doesn't even gesture at what such results might look like.

**Patch cost**: The authors must either (a) provide formal conditions under which moral trade mechanisms are incentive-compatible, including specification of enforcement, or (b) significantly downgrade their optimism about trade-based paths to good futures, acknowledging that the "blockers" in 3.5 may be the default rather than the exception.

---

## Persona: The Second-Order Catastrophist

**Core assumption**: The most dangerous proposals are those that succeed—I ask what disasters are enabled by the world where this paper's recommendations are followed.

**Steelman**: The paper correctly identifies that partial convergence plus trade is more realistic than universal moral agreement, and thoughtfully considers how different axiological views fare under various trade regimes. The analysis of "resource-compatibility" between views is genuinely novel and helps clarify when compromise is possible versus when it merely papers over irreconcilable differences.

**Signature objection**:
- **Target**: Section 3.2's framework for evaluating trade outcomes, and the paper's general optimism that "easily-satiable" views can be cheaply satisfied while linear views divide remaining resources
- **Failure mode**: The paper assumes that identifying which views are "easily satiable" is straightforward and that satisfying them creates no path dependencies. But successfully satisfying bounded views *first* creates a coalition with no remaining stake in how unbounded views divide resources—and that coalition has every incentive to defect from further cooperation once their goals are met.
- **Scenario**: Suppose common-sense morality (bounded above) gets its "common-sense utopia" in the Milky Way, while total utilitarians and other linear views are promised the rest of the accessible universe. The common-sense coalition now has no skin in the game for what happens elsewhere. When a dispute arises between total utilitarians and negative utilitarians over Andromeda, the common-sense majority has no incentive to enforce the original agreement—indeed, they may prefer the dispute to weaken both parties. Alternatively, the common-sense coalition may decide that total utilitarian expansion constitutes an existential risk (what if they tile the universe with minimal minds?) and preemptively intervene. The paper's "optimistic" scenario of satisfying bounded views first creates exactly the power imbalance that makes the remaining coordination problem unsolvable.

**Author's best reply**: We explicitly discuss threats in section 3.3 and acknowledge that "even small risks of executed threats can easily eat into the expected value." The scenario described is a form of threat, and our analysis accounts for it.

**Rebuttal**: No—the scenario I describe is *not* a threat in your sense. The common-sense coalition isn't threatening to destroy value to extract concessions; they're simply *losing interest* in enforcing agreements that no longer benefit them. Your threat analysis assumes ongoing interdependence, but your trade framework creates a world where bounded views become independent of unbounded views' fates. You've designed a system where the parties most likely to reach satisfaction first are also the parties whose continued cooperation is most needed for the remaining coordination problems. This is a *structural* failure of the proposed framework, not an adversarial exploitation of it.

**Patch cost**: The authors must either (a) argue that bounded and unbounded views will remain interdependent even after bounded views are satisfied (implausible given the paper's own framework), (b) propose mechanisms that commit bounded views to continued cooperation *before* they're satisfied (but this reintroduces the enforcement problems from the previous critique), or (c) acknowledge that the "easily satiable views first" strategy may create worse coordination problems than it solves.

---

## Persona: The Moral Parliament Dissenter

**Core assumption**: Any framework for aggregating or trading between moral views smuggles in substantive ethical commitments that privilege some views over others—the procedure is never neutral.

**Steelman**: The paper admirably avoids assuming a single correct moral view and instead asks how different views might coexist and trade. The distinction between bounded and unbounded views, and between views where goods and bads aggregate jointly versus separately, provides genuine analytical traction on which futures are achievable under moral uncertainty.

**Signature objection**:
- **Target**: The paper's core framing of "mostly-great futures" and its operationalization through resource shares and trade
- **Failure mode**: The entire framework assumes that moral views can be meaningfully compared by their "resource requirements" and that a future is "mostly-great" if it captures most achievable value "by the lights of" the correct view. But this presupposes that moral views are the kind of thing that *have* resource requirements—that they can be satisfied by controlling stuff. This is itself a substantive ethical commitment that many serious moral views reject.
- **Scenario**: Consider a deontological view on which certain actions are absolutely prohibited regardless of consequences—say, a Kantian view that prohibits treating rational beings merely as means. This view cannot be "satisfied" by giving it a resource share; it makes claims about how *all* resources everywhere must be used. A future where 99% of the universe respects Kantian constraints but 1% contains beings treated merely as means is not "99% as good" as a fully Kantian universe—it may be *worthless* or even negative, because the wrong has occurred. Similarly, certain religious views hold that the entire cosmos must glorify God; giving them a galaxy while others pursue secular ends doesn't satisfy them at all. The paper's trade framework is coherent only for views that are *already* consequentialist in structure—it cannot accommodate views that make non-fungible claims about how things must be.

**Author's best reply**: We acknowledge in section 3.4 that some views are not "resource-compatible" with others, and our analysis of bounded versus unbounded views captures some of this. Views that make absolute claims about all resources would fall into our "unbounded" category and would indeed face worse prospects under trade.

**Rebuttal**: You've misunderstood the objection. It's not that deontological views are "unbounded" in your sense—it's that they're not *in the space your framework describes at all*. Your "unbounded" views are still views that want *more* of something (more happiness, more preference-satisfaction). Deontological and many religious views don't want "more" of anything—they want certain *kinds* of actions to occur or not occur, everywhere. You can't trade with such views because there's nothing they want that you can give them in exchange for tolerating your violations elsewhere. Your framework doesn't have a category for "views that make universal demands"—you've simply assumed such views out of existence. This means your optimism about trade applies only to the subset of moral views that are already consequentialist-adjacent, which is a much smaller fraction of actual moral views than the paper acknowledges.

**Patch cost**: The authors must either (a) explicitly restrict their claims to consequentialist moral views and acknowledge that trade-based optimism doesn't extend to deontological, virtue-theoretic, or many religious views, (b) provide an argument for why such views are unlikely to be correct (which would be a major substantive ethical commitment the paper currently avoids), or (c) develop a genuinely different framework for how non-consequentialist views might coexist with others—one that doesn't reduce to resource allocation.

---

## Most Dangerous Objection Overall

**The Moral Parliament Dissenter's objection should keep the authors awake.**

The other two objections identify serious implementation problems—enforcement gaps and perverse coalition dynamics—that might be addressed through better mechanism design or more careful sequencing. But the Dissenter's objection strikes at the paper's foundational framework.

The entire analytical apparatus—resource shares, trade, bounded versus unbounded views, "mostly-great" as a fraction of achievable value—presupposes that moral views are fundamentally about *outcomes* that can be achieved through *resource control*. This is a deeply consequentialist framing that the paper never defends. If a substantial fraction of serious moral views (Kantian deontology, virtue ethics, many religious traditions) cannot even be *represented* in this framework, then the paper's conclusions about convergence and trade apply only to an arbitrarily restricted subset of the moral landscape.

This isn't a gap that can be patched with additional analysis—it requires either defending consequentialism as a meta-constraint on acceptable moral views (a huge philosophical commitment) or rebuilding the entire framework to accommodate views that make universal, non-fungible demands. The paper's apparent ecumenicism about moral views masks a deep structural bias toward views that happen to fit its analytical tools.